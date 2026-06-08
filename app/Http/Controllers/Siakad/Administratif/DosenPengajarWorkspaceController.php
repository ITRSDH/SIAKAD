<?php

namespace App\Http\Controllers\Siakad\Administratif;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DosenPengajarWorkspaceController extends Controller
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
            $kelasResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-kuliah/dosen-saya', [
                'per_page' => 100,
            ]);

            if (!$kelasResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data workspace dosen pengajar dari API.');
            }

            $kelasItems = $this->extractItems($kelasResponse->json('data', []), ['kelas_kuliah', 'data']);
            $kelasCount = count($kelasItems);
            $totalPeserta = array_sum(array_map(fn($item) => (int) ($item['peserta_terdaftar'] ?? $item['jumlah_peserta'] ?? $item['peserta_count'] ?? 0), $kelasItems));
            $totalSks = array_sum(array_map(fn($item) => (int) ($item['mata_kuliah']['sks'] ?? $item['mata_kuliah']['jumlah_sks'] ?? $item['sks'] ?? 0), $kelasItems));

            $kpis = [
                [
                    'title' => 'Kelas Ajar',
                    'value' => number_format($kelasCount),
                    'description' => 'Total kelas kuliah yang sedang diampu oleh dosen login.',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'theme' => 'primary',
                ],
                [
                    'title' => 'Total Peserta',
                    'value' => number_format($totalPeserta),
                    'description' => 'Akumulasi mahasiswa yang berada di seluruh kelas ajar.',
                    'icon' => 'fas fa-users',
                    'theme' => 'success',
                ],
                [
                    'title' => 'Beban SKS',
                    'value' => number_format($totalSks),
                    'description' => 'Total bobot SKS dari kelas yang saat ini diampu.',
                    'icon' => 'fas fa-book-open',
                    'theme' => 'warning',
                ],
                [
                    'title' => 'Siklus Kerja',
                    'value' => '3',
                    'description' => 'Fokus kerja utama: pertemuan, presensi, dan penilaian.',
                    'icon' => 'fas fa-chart-pie',
                    'theme' => 'info',
                ],
            ];

            $workflowGroups = [
                [
                    'title' => 'Kelas Ajar',
                    'description' => 'Mulai dari membaca daftar kelas yang diampu dan konteks pesertanya.',
                    'links' => [
                        ['label' => 'Workspace Dosen', 'route' => route('workspace.dosen-pengajar')],
                        ['label' => 'Profil Saya', 'route' => route('profile')],
                    ],
                ],
                [
                    'title' => 'Pertemuan dan Presensi',
                    'description' => 'Catat jalannya kuliah, generate peserta presensi, dan lihat rekap kehadiran.',
                    'links' => [
                        ['label' => 'Pertemuan & Presensi', 'route' => route('dosen.pertemuan-presensi.index')],
                    ],
                ],
                [
                    'title' => 'Penilaian Kelas',
                    'description' => 'Kelola komponen penilaian, isi nilai, lalu publish final.',
                    'links' => [
                        ['label' => 'Penilaian Kelas', 'route' => route('dosen.penilaian.index')],
                    ],
                ],
            ];

            $recentClasses = array_slice($kelasItems, 0, 6);

            return view('administratif.dosen_pengajar.index', compact(
                'kpis',
                'workflowGroups',
                'recentClasses',
                'kelasCount',
                'totalPeserta',
                'totalSks'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function extractItems(array $payload, array $keys): array
    {
        foreach ($keys as $key) {
            if (isset($payload[$key]) && is_array($payload[$key])) {
                return $payload[$key];
            }
        }

        return array_is_list($payload) ? $payload : [];
    }
}
