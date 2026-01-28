<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class KelasMKController extends Controller
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
            // Ambil data kelas mata kuliah dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-mk');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data All Kelas Mata Kuliah dari API');
            }

            $kelasmk = $response->json()['data'] ?? [];

            // dd($kelasmk);

            // Kirim kedua data ke view
            return view('masterdata.kelas_mk.index', compact(
                'kelasmk',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        // Ambil parameter dari request
        $prodiId = $request->input('nama_prodi');
        $selectedSemester = $request->input('semester'); // Ambil semester dari request

        // Bangun URL API
        $apiUrl = $this->apiUrl . 'kelas-mk/create';

        $queryParams = [];
        if ($prodiId) {
            $queryParams['nama_prodi'] = $prodiId;
        }
        if ($selectedSemester !== null) {
            $queryParams['semester'] = $selectedSemester;
        }

        if (!empty($queryParams)) {
            $apiUrl .= '?' . http_build_query($queryParams);
        }

        $response = Http::withToken($this->apiToken)->get($apiUrl);
        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengambil data All Kelas Mata Kuliah dari API');
        }

        $apiData = $response->json()['data'] ?? [];

        // Cek apakah ini hanya untuk menampilkan list prodi
        if (isset($apiData['prodi_list']) && !empty($apiData['prodi_list'])) {
            $prodiList = $apiData['prodi_list'];
            $tahun_akademik = $apiData['tahun_akademik'];
            $semester = $apiData['semester'];

            return view('masterdata.kelas_mk.create', compact(
                'prodiList',
                'tahun_akademik',
                'semester'
            ));
        }

        // Ekstrak data yang diperlukan
        $tahun_akademik = $apiData['tahun_akademik'] ?? [];
        $semester = $apiData['semester'] ?? [];
        $jenis_kelas = $apiData['jenis_kelas'] ?? [];
        $prodi_data = $apiData['prodi'] ?? [];

        // Siapkan data untuk dropdown dalam format yang mudah digunakan
        $dropdownData = [
            'prodi' => collect($prodi_data)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'nama_prodi' => $item['nama_prodi'],
                    'kurikulum' => collect($item['kurikulum'])->map(function ($kur) {
                        $data = [
                            'id' => $kur['id'],
                            'nama_kurikulum' => $kur['nama_kurikulum']
                        ];

                        // Gunakan mata_kuliah_by_semester jika tersedia, otherwise gunakan mata_kuliah biasa
                        if (isset($kur['mata_kuliah_by_semester']) && !empty($kur['mata_kuliah_by_semester'])) {
                            $data['mata_kuliah_by_semester'] = collect($kur['mata_kuliah_by_semester'])->map(function ($semesterGroup) {
                                return [
                                    'semester' => $semesterGroup['semester'],
                                    'jumlah_mk' => $semesterGroup['jumlah_mk'],
                                    'mata_kuliah' => $semesterGroup['mata_kuliah']
                                ];
                            });
                            // Juga sediakan semua mata kuliah tanpa pengelompokan jika diperlukan
                            $allMataKuliah = [];
                            foreach ($kur['mata_kuliah_by_semester'] as $semesterGroup) {
                                $allMataKuliah = array_merge($allMataKuliah, $semesterGroup['mata_kuliah']);
                            }
                            $data['mata_kuliah'] = $allMataKuliah;
                        } else {
                            $data['mata_kuliah'] = $kur['mata_kuliah'] ?? [];
                        }

                        $data['kelas_pararel'] = $item['kelas_pararel'] ?? [];

                        return $data;
                    }),
                    'kelas_pararel' => $item['kelas_pararel'] ?? []
                ];
            }),
            'jenis_kelas' => $jenis_kelas
        ];

        return view('masterdata.kelas_mk.create', compact(
            'tahun_akademik',
            'semester',
            'dropdownData'
        ));
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'kelas-mk', $request->all());
            if ($response->successful()) {
                return $response->successful()
                    ? redirect()->route('kelas-mk.index')->with('success', 'Data berhasil disimpan')
                    : back()->withErrors($response->json('message'));
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data ke API',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id, Request $request)
    {
        // =========================
        // Ambil filter dari request
        // =========================
        $queryParams = array_filter([
            'nama_prodi' => $request->input('nama_prodi'),
            'semester'   => $request->input('semester'),
        ], fn($v) => $v !== null && $v !== '');

        // =========================
        // Build API URL
        // =========================
        $apiUrl = $this->apiUrl . "kelas-mk/{$id}/edit";

        if (!empty($queryParams)) {
            $apiUrl .= '?' . http_build_query($queryParams);
        }

        $response = Http::withToken($this->apiToken)->get($apiUrl);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengambil data Kelas Mata Kuliah dari API');
        }

        $apiData = $response->json('data') ?? [];

        // =========================
        // Ambil data utama dari API
        // =========================
        $kelasMk        = $apiData['kelas_mk'] ?? [];
        $dropdown       = $apiData['dropdown'] ?? [];
        $filters        = $apiData['filters'] ?? [];
        $tahunSemester  = $apiData['tahun_semester'] ?? null; // 🔥 BARU

        $selectedSemester = $filters['semester'] ?? null;

        // =========================
        // Normalisasi Dropdown (Aman ke Blade)
        // =========================
        $dropdownData = [
            'kurikulum' => collect($dropdown['kurikulum'] ?? [])
                ->map(fn($kur) => [
                    'id' => $kur['id'],
                    'nama_kurikulum' => $kur['nama'],
                    'mata_kuliah_by_semester' =>
                    collect($kur['mata_kuliah_by_semester'] ?? [])
                        ->map(fn($group) => [
                            'semester' => $group['semester'],
                            'jumlah_mk' => $group['jumlah_mk'],
                            'mata_kuliah' => $group['mata_kuliah'],
                        ])
                        ->values()
                ])
                ->values()
                ->toArray(),

            'kelas_pararel' => $dropdown['kelas_pararel'] ?? [],
            'jenis_kelas'   => $dropdown['jenis_kelas'] ?? [],
        ];

        // =========================
        // Kirim ke Blade
        // =========================
        return view('masterdata.kelas_mk.edit', [
            'id'               => $id,
            'kelasMk'          => $kelasMk,
            'dropdownData'     => $dropdownData,
            'selectedSemester' => $selectedSemester,
            'filters'          => $filters,
            'tahunSemester'    => $tahunSemester, // 🔥 BARU
        ]);
    }



    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "kelas-mk/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('kelas-mk.index')->with('success', 'Kelas MK berhasil diperbarui.');
            }

            return back()->with('error', 'Gagal memperbarui data di API')
                ->withErrors($response->json())
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "kelas-mk/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data di API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
