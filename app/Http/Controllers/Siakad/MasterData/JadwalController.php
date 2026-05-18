<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JadwalController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index($id_kelas_kuliah)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "jadwal-kuliah/kelas/{$id_kelas_kuliah}");
            $kelasResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kelas-kuliah/{$id_kelas_kuliah}");
            $ruangResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . 'ruang-kuliah');

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Gagal mengambil data jadwal'
                ], 500);
            }

            $jadwalKelas = $response->json('data') ?? [];
            $kelasKuliah = $kelasResponse->successful() ? ($kelasResponse->json('data') ?? []) : [];
            $ruangKuliah = $ruangResponse->successful() ? ($ruangResponse->json('data') ?? []) : [];

            return view('masterdata.kelaskuliah.partials.jadwal-kelas', [
                'jadwalKelas' => $jadwalKelas,
                'kelasKuliah' => $kelasKuliah,
                'ruangKuliah' => $ruangKuliah,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $id_kelas_kuliah)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . "jadwal-kuliah/kelas/{$id_kelas_kuliah}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menambah data jadwal kuliah ke API',
                'errors' => $response->json('errors') ?? $response->json(),
                'data' => $response->json('data') ?? [],
                'meta' => $response->json('meta') ?? [],
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "jadwal-kuliah/{$id}");

            if (!$response->successful()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengambil data'
                ], 500);
            }

            $apiData = $response->json()['data'] ?? null;

            return response()->json([
                'status' => 'success',
                'data' => $apiData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "jadwal-kuliah/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal memperbarui data di API',
                'errors' => $response->json('errors') ?? $response->json(),
                'data' => $response->json('data') ?? [],
                'meta' => $response->json('meta') ?? [],
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "jadwal-kuliah/{$id}");

            if (!$response->successful()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal hapus data'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
