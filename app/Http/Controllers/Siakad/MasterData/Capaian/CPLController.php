<?php

namespace App\Http\Controllers\Siakad\MasterData\Capaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CPLController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(Request $request, $id_prodi)
    {
        return view('masterdata.data_capaian.cpl.index', compact('id_prodi'));
    }

    public function getData(Request $request, $id_prodi)
    {
        try {

            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "cpl/prodi/{$id_prodi}", $request->all());

            if ($response->successful()) {

                $data = $response->json();

                return response()->json([
                    'data' => $data['data'] ?? []
                ]);
            }

            return response()->json([
                'data' => [],
                'message' => 'Gagal mengambil data di API'
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "cpl/prodi/{$id_prodi}", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Capaian Pembelajaran Lulusan berhasil ditambahkan',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data',
                'errors' => $response->json()
            ], 422);
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
                ->get($this->apiUrl . "cpl/{$id}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id, $id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "cpl/{$id}/prodi/{$id_prodi}", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Capaian Pembelajaran Lulusan berhasil diperbarui',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
                'errors' => $response->json()
            ], 422);
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
                ->delete($this->apiUrl . "cpl/{$id}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Capaian Pembelajaran Lulusan berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeIndikatorKinerja(Request $request, $id_cpl)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "indikator-kinerja/{$id_cpl}", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Indikator Kinerja berhasil ditambahkan',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateIndikatorKinerja(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "indikator-kinerja/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Indikator Kinerja berhasil diperbarui',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyIndikatorKinerja($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "indikator-kinerja/{$id}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Indikator Kinerja berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
