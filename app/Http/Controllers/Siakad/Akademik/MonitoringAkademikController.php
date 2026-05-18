<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class MonitoringAkademikController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index(Request $request)
    {
        try {
            $periodeKrsResponse = $this->apiRequest('periode-krs');
            $kelasKuliahResponse = $this->apiRequest('kelas-kuliah');
            $khsResponse = $this->apiRequest('khs');
            $transkripResponse = $this->apiRequest('transkrip');

            if (
                !$periodeKrsResponse->successful() ||
                !$kelasKuliahResponse->successful() ||
                !$khsResponse->successful() ||
                !$transkripResponse->successful()
            ) {
                return back()->with('error', 'Gagal mengambil data monitoring akademik dari API.');
            }

            $periodeKrs = collect($periodeKrsResponse->json()['data'] ?? []);
            $kelasKuliah = collect($kelasKuliahResponse->json()['data'] ?? []);
            $khs = collect($khsResponse->json()['data'] ?? []);
            $transkrip = collect($transkripResponse->json()['data'] ?? []);

            $filters = [
                'semester_id' => (string) $request->query('semester_id', ''),
                'prodi_id' => (string) $request->query('prodi_id', ''),
                'status' => (string) $request->query('status', ''),
                'search' => trim((string) $request->query('search', '')),
            ];

            $semesterOptions = $this->extractSemesterOptions($periodeKrs, $kelasKuliah, $khs);
            $prodiOptions = $this->extractProdiOptions($kelasKuliah);

            $periodeKrs = $this->filterPeriodeKrs($periodeKrs, $filters);
            $kelasKuliah = $this->filterKelasKuliah($kelasKuliah, $filters);
            $khs = $this->filterKhs($khs, $filters);
            $transkrip = $this->filterTranskrip($transkrip, $filters);

            $periodeAktif = $this->resolvePeriodeAktif($periodeKrs);

            $summary = [
                'periode_krs_total' => $periodeKrs->count(),
                'kelas_kuliah_total' => $kelasKuliah->count(),
                'khs_total' => $khs->count(),
                'transkrip_total' => $transkrip->count(),
                'mahasiswa_lulus_sks' => $transkrip->filter(fn (array $item) => (int) ($item['total_sks_lulus'] ?? 0) > 0)->count(),
            ];

            $kelasSummary = [
                'total' => $kelasKuliah->count(),
                'punya_pengajar' => $kelasKuliah->filter(fn (array $item) => !empty($item['dosen_pengajar']) && count((array) $item['dosen_pengajar']) > 0)->count(),
                'punya_peserta' => $kelasKuliah->filter(fn (array $item) => (int) ($item['peserta_terdaftar'] ?? 0) > 0)->count(),
                'total_peserta' => $kelasKuliah->sum(fn (array $item) => (int) ($item['peserta_terdaftar'] ?? 0)),
            ];

            $khsIpkBuckets = [
                '>= 3.50' => $khs->filter(fn (array $item) => (float) ($item['ip_semester'] ?? 0) >= 3.5)->count(),
                '3.00 - 3.49' => $khs->filter(fn (array $item) => ($item['ip_semester'] ?? 0) >= 3.0 && ($item['ip_semester'] ?? 0) < 3.5)->count(),
                '< 3.00' => $khs->filter(fn (array $item) => (float) ($item['ip_semester'] ?? 0) < 3.0)->count(),
            ];

            $transkripIpkBuckets = [
                '>= 3.50' => $transkrip->filter(fn (array $item) => (float) ($item['ipk'] ?? 0) >= 3.5)->count(),
                '3.00 - 3.49' => $transkrip->filter(fn (array $item) => ($item['ipk'] ?? 0) >= 3.0 && ($item['ipk'] ?? 0) < 3.5)->count(),
                '< 3.00' => $transkrip->filter(fn (array $item) => (float) ($item['ipk'] ?? 0) < 3.0)->count(),
            ];

            $recent = [
                'periode_krs' => $periodeKrs->take(5),
                'kelas_kuliah' => $kelasKuliah->take(5),
                'khs' => $khs->take(5),
                'transkrip' => $transkrip->take(5),
            ];

            return view('akademik.monitoring.index', compact(
                'summary',
                'kelasSummary',
                'khsIpkBuckets',
                'transkripIpkBuckets',
                'periodeKrs',
                'periodeAktif',
                'recent',
                'filters',
                'semesterOptions',
                'prodiOptions'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function apiRequest(string $endpoint): Response
    {
        return Http::withToken(session('access_token'))
            ->acceptJson()
            ->get(rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/'));
    }

    private function resolvePeriodeAktif(Collection $periodeKrs): ?array
    {
        $now = now()->toDateString();

        $currentByDate = $periodeKrs->first(function (array $item) use ($now) {
            $mulai = $item['tanggal_mulai'] ?? null;
            $selesai = $item['tanggal_selesai'] ?? null;

            return filled($mulai) && filled($selesai) && $mulai <= $now && $selesai >= $now;
        });

        return $currentByDate ?? $periodeKrs->first();
    }

    private function extractSemesterOptions(Collection $periodeKrs, Collection $kelasKuliah, Collection $khs): Collection
    {
        return $periodeKrs
            ->map(function (array $item) {
                return [
                    'id' => $item['id_semester'] ?? ($item['semester']['id'] ?? null),
                    'label' => $item['semester']['nama_semester'] ?? ($item['semester']['kode_semester'] ?? null),
                ];
            })
            ->merge($kelasKuliah->map(function (array $item) {
                return [
                    'id' => $item['id_semester'] ?? null,
                    'label' => $item['semester'] ?? null,
                ];
            }))
            ->merge($khs->map(function (array $item) {
                $semester = $item['semester'] ?? [];

                return [
                    'id' => $item['id_semester'] ?? ($semester['id'] ?? null),
                    'label' => $semester['nama_semester'] ?? ($item['nama_semester'] ?? null),
                ];
            }))
            ->filter(fn (array $item) => filled($item['id']) && filled($item['label']))
            ->unique('id')
            ->sortBy('label')
            ->values();
    }

    private function extractProdiOptions(Collection $kelasKuliah): Collection
    {
        return $kelasKuliah
            ->map(function (array $item) {
                return [
                    'id' => $item['id_prodi'] ?? null,
                    'label' => is_array($item['prodi'] ?? null)
                        ? ($item['prodi']['nama_prodi'] ?? null)
                        : ($item['prodi'] ?? $item['prodi_label'] ?? null),
                ];
            })
            ->filter(fn (array $item) => filled($item['id']) && filled($item['label']))
            ->unique('id')
            ->sortBy('label')
            ->values();
    }

    private function filterPeriodeKrs(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['semester_id']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => ($item['id_semester'] ?? ($item['semester']['id'] ?? null)) === $filters['semester_id']
            ))
            ->when(filled($filters['status']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['status'] ?? '') === $filters['status']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $label = strtolower((string) ($item['semester']['nama_semester'] ?? $item['semester']['kode_semester'] ?? $item['catatan'] ?? ''));

                return str_contains($label, $needle);
            }))
            ->values();
    }

    private function filterKelasKuliah(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['semester_id']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['id_semester'] ?? '') === $filters['semester_id']
            ))
            ->when(filled($filters['prodi_id']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['id_prodi'] ?? '') === $filters['prodi_id']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['nama_kelas'] ?? $item['nama'] ?? ''),
                    (string) ($item['mata_kuliah']['nama_mk'] ?? $item['mata_kuliah']['nama_mata_kuliah'] ?? ''),
                    (string) ($item['mata_kuliah']['kode_mk'] ?? ''),
                    (string) (is_array($item['prodi'] ?? null) ? ($item['prodi']['nama_prodi'] ?? '') : ($item['prodi'] ?? '')),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }

    private function filterKhs(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['semester_id']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['id_semester'] ?? ($item['semester']['id'] ?? '')) === $filters['semester_id']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['mahasiswa']['nama_mahasiswa'] ?? ''),
                    (string) ($item['mahasiswa']['nim'] ?? ''),
                    (string) ($item['semester']['nama_semester'] ?? $item['nama_semester'] ?? ''),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }

    private function filterTranskrip(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['mahasiswa']['nama_mahasiswa'] ?? ''),
                    (string) ($item['mahasiswa']['nim'] ?? ''),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }
}
