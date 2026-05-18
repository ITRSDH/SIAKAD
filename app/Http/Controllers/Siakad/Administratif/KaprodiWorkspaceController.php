<?php

namespace App\Http\Controllers\Siakad\Administratif;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class KaprodiWorkspaceController extends Controller
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
                'prodi' => Http::withToken($this->apiToken)->get($this->apiUrl . 'prodi'),
                'mahasiswa' => Http::withToken($this->apiToken)->get($this->apiUrl . 'mahasiswa'),
                'kurikulum' => Http::withToken($this->apiToken)->get($this->apiUrl . 'kurikulum'),
                'kelasKuliah' => Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-kuliah'),
                'tugasAkhir' => Http::withToken($this->apiToken)->get($this->apiUrl . 'tugas-akhir'),
                'yudisium' => Http::withToken($this->apiToken)->get($this->apiUrl . 'yudisium'),
                'kelulusan' => Http::withToken($this->apiToken)->get($this->apiUrl . 'kelulusan'),
            ];

            foreach ($responses as $response) {
                if (!$response->successful()) {
                    return back()->with('error', 'Gagal mengambil data workspace Kaprodi dari API.');
                }
            }

            $profile = session('profile', []);
            $prodiItems = $responses['prodi']->json('data.prodi', []);
            $mahasiswaItems = $responses['mahasiswa']->json('data.mahasiswa', []);
            $managedProdi = $this->resolveManagedProdi($prodiItems, $profile);
            $managedProdiIds = array_values(array_filter(array_map(fn ($item) => $item['id'] ?? null, $managedProdi)));
            $mahasiswaProdiMap = $this->buildMahasiswaProdiMap($mahasiswaItems);

            $kurikulumItems = $this->extractItems($responses['kurikulum']->json('data', []), ['kurikulum']);
            $kelasItems = $this->extractItems($responses['kelasKuliah']->json('data', []), ['kelas_kuliah']);
            $tugasAkhirItems = $this->extractItems($responses['tugasAkhir']->json('data', []), ['tugas_akhir']);
            $yudisiumItems = $this->extractItems($responses['yudisium']->json('data', []), ['yudisium']);
            $kelulusanItems = $this->extractItems($responses['kelulusan']->json('data', []), ['kelulusan']);

            $filteredKurikulum = $this->filterByProdi($kurikulumItems, $managedProdiIds, ['id_prodi', 'prodi.id']);
            $filteredKelas = $this->filterByProdi($kelasItems, $managedProdiIds, ['id_prodi', 'prodi.id']);
            $filteredTugasAkhir = $this->filterByMahasiswaMap($tugasAkhirItems, $managedProdiIds, $mahasiswaProdiMap);
            $filteredYudisium = $this->filterByMahasiswaMap($yudisiumItems, $managedProdiIds, $mahasiswaProdiMap);
            $filteredKelulusan = $this->filterByMahasiswaMap($kelulusanItems, $managedProdiIds, $mahasiswaProdiMap);

            $primaryProdiId = $managedProdiIds[0] ?? null;

            $kpis = [
                [
                    'title' => 'Prodi Dikelola',
                    'value' => number_format(count($managedProdi)),
                    'description' => 'Program studi yang berada dalam tanggung jawab kaprodi login.',
                    'icon' => 'fas fa-building',
                    'theme' => 'primary',
                ],
                [
                    'title' => 'Kurikulum',
                    'value' => number_format(count($filteredKurikulum)),
                    'description' => 'Kurikulum aktif dan historis yang terkait dengan prodi kelolaan.',
                    'icon' => 'fas fa-book',
                    'theme' => 'success',
                ],
                [
                    'title' => 'Kelas Kuliah',
                    'value' => number_format(count($filteredKelas)),
                    'description' => 'Offering kelas yang menjadi area kontrol akademik prodi.',
                    'icon' => 'fas fa-chalkboard',
                    'theme' => 'warning',
                ],
                [
                    'title' => 'Akhir Studi',
                    'value' => number_format(count($filteredYudisium) + count($filteredKelulusan)),
                    'description' => 'Snapshot mahasiswa yang sudah masuk fase yudisium dan kelulusan.',
                    'icon' => 'fas fa-user-graduate',
                    'theme' => 'info',
                ],
            ];

            $workflowGroups = [
                [
                    'title' => 'Kepemimpinan Prodi',
                    'description' => 'Kontrol struktur akademik inti pada program studi yang dipimpin.',
                    'links' => [
                        ['label' => 'Program Studi', 'route' => route('aktor-akademik.kaprodi')],
                        ['label' => 'Aktor Akademik', 'route' => route('aktor-akademik.index')],
                        ['label' => 'Data Dosen', 'route' => route('dosen.index')],
                    ],
                ],
                [
                    'title' => 'Kurikulum dan Capaian',
                    'description' => 'Mutu akademik prodi dijaga melalui kurikulum, mata kuliah, dan capaian pembelajaran.',
                    'links' => [
                        ['label' => 'Kurikulum', 'route' => route('kurikulum.index')],
                        ['label' => 'Mata Kuliah', 'route' => $primaryProdiId ? route('mata-kuliah.index', $primaryProdiId) : route('mata-kuliah.indexProdi')],
                        ['label' => 'Capaian Pembelajaran', 'route' => $primaryProdiId ? route('capaian.detailProdi', $primaryProdiId) : route('capaian.indexProdi')],
                    ],
                ],
                [
                    'title' => 'Operasional Prodi',
                    'description' => 'Pembukaan kelas dan monitoring semester berjalan.',
                    'links' => [
                        ['label' => 'Kelas Kuliah', 'route' => route('kelas-kuliah.index')],
                        ['label' => 'Monitoring Akademik', 'route' => route('akademik.monitoring')],
                    ],
                ],
                [
                    'title' => 'Akhir Studi Prodi',
                    'description' => 'Pengawalan mahasiswa dari tugas akhir sampai kelulusan.',
                    'links' => [
                        ['label' => 'Tugas Akhir', 'route' => route('tugas-akhir.index')],
                        ['label' => 'Yudisium', 'route' => route('yudisium.index')],
                        ['label' => 'Kelulusan', 'route' => route('kelulusan.index')],
                        ['label' => 'Monitoring Akhir Studi', 'route' => route('akhir-studi.monitoring')],
                    ],
                ],
            ];

            return view('administratif.kaprodi.index', compact(
                'kpis',
                'managedProdi',
                'filteredKurikulum',
                'filteredKelas',
                'filteredTugasAkhir',
                'filteredYudisium',
                'filteredKelulusan',
                'workflowGroups',
                'primaryProdiId'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function resolveManagedProdi(array $prodiItems, array $profile): array
    {
        $profileId = $profile['id'] ?? null;
        $profileName = trim((string) ($profile['nama_dosen'] ?? ''));

        $managed = array_values(array_filter($prodiItems, function ($item) use ($profileId, $profileName) {
            if ($profileId && (string) ($item['id_kaprodi'] ?? '') === (string) $profileId) {
                return true;
            }

            $kaprodiName = trim((string) ($item['kaprodi']['nama_dosen'] ?? ''));
            return $profileName !== '' && $kaprodiName !== '' && strcasecmp($kaprodiName, $profileName) === 0;
        }));

        if ($managed !== []) {
            return $managed;
        }

        return array_values(array_filter($prodiItems, fn ($item) => !empty($item['id_kaprodi'] ?? null)));
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

    protected function buildMahasiswaProdiMap(array $mahasiswaItems): array
    {
        $map = [];

        foreach ($mahasiswaItems as $item) {
            $mahasiswaId = $item['id'] ?? null;
            $prodiId = $item['id_prodi'] ?? null;

            if ($mahasiswaId && $prodiId) {
                $map[(string) $mahasiswaId] = (string) $prodiId;
            }
        }

        return $map;
    }

    protected function filterByProdi(array $items, array $prodiIds, array $paths): array
    {
        if ($prodiIds === []) {
            return $items;
        }

        return array_values(array_filter($items, function ($item) use ($prodiIds, $paths) {
            foreach ($paths as $path) {
                $value = data_get($item, $path);
                if ($value !== null && in_array((string) $value, array_map('strval', $prodiIds), true)) {
                    return true;
                }
            }

            return false;
        }));
    }

    protected function filterByMahasiswaMap(array $items, array $prodiIds, array $mahasiswaProdiMap): array
    {
        if ($prodiIds === []) {
            return $items;
        }

        $prodiIds = array_map('strval', $prodiIds);

        return array_values(array_filter($items, function ($item) use ($prodiIds, $mahasiswaProdiMap) {
            $mahasiswaId = (string) ($item['id_mahasiswa'] ?? $item['mahasiswa']['id'] ?? '');
            $prodiId = $mahasiswaProdiMap[$mahasiswaId] ?? null;

            return $prodiId !== null && in_array($prodiId, $prodiIds, true);
        }));
    }
}
