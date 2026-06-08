<?php

namespace App\Http\Controllers\Siakad\Administratif;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BaakWorkspaceController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index()
    {
        try {
            $responses = [
                'tahunAkademik' => Http::withToken($this->apiToken)->get($this->apiUrl . 'tahun-akademik'),
                'periodeKrs' => Http::withToken($this->apiToken)->get($this->apiUrl . 'periode-krs'),
                'kelasKuliah' => Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-kuliah'),
                'khs' => Http::withToken($this->apiToken)->get($this->apiUrl . 'khs'),
                'khsImportHistory' => Http::withToken($this->apiToken)->get($this->apiUrl . 'khs/import/history'),
                'transkrip' => Http::withToken($this->apiToken)->get($this->apiUrl . 'transkrip'),
                'yudisium' => Http::withToken($this->apiToken)->get($this->apiUrl . 'yudisium'),
                'kelulusan' => Http::withToken($this->apiToken)->get($this->apiUrl . 'kelulusan'),
                'wisuda' => Http::withToken($this->apiToken)->get($this->apiUrl . 'wisuda/periode'),
            ];

            foreach ($responses as $response) {
                if (!$response->successful()) {
                    return back()->with('error', 'Gagal mengambil data workspace BAAK dari API.');
                }
            }

            $tahunAkademikItems = $this->normalizeList($responses['tahunAkademik']->json('data', []));
            $semesterAktif = $this->findActiveSemester($tahunAkademikItems);
            $periodeItems = $this->normalizeList($responses['periodeKrs']->json('data', []));
            $periodeAktif = collect($periodeItems)->first(function ($item) {
                $status = strtolower((string) ($item['status'] ?? $item['status_periode'] ?? ''));

                return $status === 'aktif'
                    || ($item['is_active'] ?? false)
                    || $status === 'dibuka';
            });

            $kelasItems = $this->normalizeList($responses['kelasKuliah']->json('data', []));
            $khsItems = $this->normalizeList($responses['khs']->json('data', []));
            $khsImportHistoryItems = $this->normalizeList($responses['khsImportHistory']->json('data', []));
            $transkripItems = $this->normalizeList($responses['transkrip']->json('data', []));
            $yudisiumItems = $this->normalizeList($responses['yudisium']->json('data', []));
            $kelulusanItems = $this->normalizeList($responses['kelulusan']->json('data', []));
            $wisudaItems = $this->normalizeList($responses['wisuda']->json('data', []));
            $semesterAktifLabel = $this->resolveSemesterLabel($semesterAktif);
            $periodeAktifLabel = $this->resolvePeriodeLabel($periodeAktif);

            $kpis = [
                [
                    'title' => 'Semester Aktif',
                    'value' => $semesterAktifLabel,
                    'description' => 'Acuan utama seluruh transaksi akademik berjalan.',
                    'icon' => 'fas fa-calendar-alt',
                    'theme' => 'primary',
                    'action_label' => 'Kelola Tahun Akademik',
                    'action_url' => route('tahun-akademik.index'),
                ],
                [
                    'title' => 'Periode KRS Aktif',
                    'value' => $periodeAktifLabel,
                    'description' => 'Periode layanan KRS yang sedang digunakan mahasiswa.',
                    'icon' => 'fas fa-clipboard-list',
                    'theme' => 'success',
                    'action_label' => 'Kelola Periode KRS',
                    'action_url' => route('periode-krs.index'),
                ],
                [
                    'title' => 'Kelas Kuliah',
                    'value' => number_format(count($kelasItems)),
                    'description' => 'Total offering kelas kuliah yang sedang terdaftar di sistem.',
                    'icon' => 'fas fa-chalkboard',
                    'theme' => 'warning',
                    'action_label' => 'Kelola Kelas',
                    'action_url' => route('kelas-kuliah.index'),
                ],
                [
                    'title' => 'Wisuda',
                    'value' => number_format(count($wisudaItems)),
                    'description' => 'Periode wisuda yang sudah dibentuk sebagai layanan akhir studi.',
                    'icon' => 'fas fa-user-graduate',
                    'theme' => 'info',
                    'action_label' => 'Kelola Wisuda',
                    'action_url' => route('wisuda.periode.index'),
                ],
            ];

            $workflowGroups = [
                [
                    'title' => 'Setup Akademik',
                    'description' => 'Fondasi semester, periode, kurikulum, dan aktor akademik.',
                    'links' => [
                        ['label' => 'Tahun Akademik', 'route' => route('tahun-akademik.index')],
                        ['label' => 'Periode KRS', 'route' => route('periode-krs.index')],
                        ['label' => 'Program Studi', 'route' => route('prodi.index')],
                        ['label' => 'Aktor Akademik', 'route' => route('aktor-akademik.index')],
                    ],
                ],
                [
                    'title' => 'Operasional Semester',
                    'description' => 'Pembukaan kelas, pengelolaan ruang, dan kontrol operasional akademik.',
                    'links' => [
                        ['label' => 'Kelas Kuliah', 'route' => route('kelas-kuliah.index')],
                        ['label' => 'Ruang Kuliah', 'route' => route('ruang-kuliah.index')],
                        ['label' => 'Monitoring Akademik', 'route' => route('akademik.monitoring')],
                        ['label' => 'Administrasi Studi Mahasiswa', 'route' => route('akademik.administrasi-studi.index')],
                    ],
                ],
                [
                    'title' => 'Hasil Studi',
                    'description' => 'Rekap semester dan hasil studi lintas semester.',
                    'links' => [
                        ['label' => 'Monitoring Akademik', 'route' => route('akademik.monitoring')],
                        ['label' => 'Data Mahasiswa', 'route' => route('mahasiswa.index')],
                        ['label' => 'Monitoring Akhir Studi', 'route' => route('akhir-studi.monitoring')],
                    ],
                ],
                [
                    'title' => 'Akhir Studi',
                    'description' => 'Konsolidasi tugas akhir, yudisium, kelulusan, dan wisuda.',
                    'links' => [
                        ['label' => 'Monitoring Akhir Studi', 'route' => route('akhir-studi.monitoring')],
                        ['label' => 'Tugas Akhir', 'route' => route('tugas-akhir.index')],
                        ['label' => 'Yudisium', 'route' => route('yudisium.index')],
                        ['label' => 'Kelulusan', 'route' => route('kelulusan.index')],
                        ['label' => 'Wisuda', 'route' => route('wisuda.periode.index')],
                    ],
                ],
            ];

            $statusSummary = [
                'khs' => count($khsItems),
                'khs_import' => count($khsImportHistoryItems),
                'khs_import_failed' => collect($khsImportHistoryItems)->where('status', 'failed')->count(),
                'khs_draft' => collect($khsItems)->filter(fn($item) => empty($item['is_final']))->count(),
                'transkrip' => count($transkripItems),
                'yudisium' => count($yudisiumItems),
                'kelulusan' => count($kelulusanItems),
            ];

            return view('administratif.baak.index', compact(
                'kpis',
                'workflowGroups',
                'semesterAktif',
                'semesterAktifLabel',
                'periodeAktif',
                'periodeAktifLabel',
                'statusSummary'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function findActiveSemester(array $tahunAkademikItems): array
    {
        foreach ($tahunAkademikItems as $tahun) {
            foreach (($tahun['semester'] ?? []) as $semester) {
                if (strtolower((string) ($semester['status'] ?? '')) === 'aktif' || ($semester['is_active'] ?? false)) {
                    return $semester;
                }
            }
        }

        return [];
    }

    protected function normalizeList(array $payload): array
    {
        return array_is_list($payload) ? $payload : [];
    }

    protected function resolveSemesterLabel(array $semester): string
    {
        return (string) (
            $semester['nama_semester']
            ?? $semester['nama']
            ?? $semester['semester_akademik']
            ?? $semester['kode_semester']
            ?? '-'
        );
    }

    protected function resolvePeriodeLabel(?array $periode): string
    {
        if (!$periode) {
            return '-';
        }

        $semester = $periode['semester'] ?? [];
        $semesterLabel = is_array($semester)
            ? ($semester['nama_semester'] ?? $semester['kode_semester'] ?? null)
            : null;

        return (string) (
            $periode['nama_periode']
            ?? $periode['nama']
            ?? $periode['kode']
            ?? $semesterLabel
            ?? '-'
        );
    }
}
