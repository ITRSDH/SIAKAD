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

    public function create()
    {
        $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-mk/create');
        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengambil data All Kelas Mata Kuliah dari API');
        }

        $apiData = $response->json()['data'] ?? [];

        // Ekstrak data yang diperlukan
        $tahun_akademik = $apiData['tahun_akademik'] ?? [];
        $semester = $apiData['semester'] ?? [];
        $jenis_kelas = $apiData['jenis_kelas'] ?? []; // Perhatikan: bukan 'jenis_kelas'
        $prodi_data = $apiData['prodi'] ?? [];

        // Siapkan data untuk dropdown dalam format yang mudah digunakan
        $dropdownData = [
            'prodi' => collect($prodi_data)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'nama_prodi' => $item['nama_prodi'],
                    'kurikulum' => collect($item['kurikulum'])->map(function ($kur) {
                        return [
                            'id' => $kur['id'],
                            'nama_kurikulum' => $kur['nama_kurikulum'],
                            'mata_kuliah' => $kur['mata_kuliah']
                        ];
                    }),
                    'kelas_pararel' => $item['kelas_pararel']
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
}
