<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DosenPengajarKelasController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(DropdownService $dropdownService, $id_kelas_kuliah)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "dosen-pengajar-kelas/kelas/{$id_kelas_kuliah}");

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Gagal mengambil data dosen'
                ], 500);
            }

            $dosen_pa = $response->json('data') ?? [];
            $totalSks = collect($dosen_pa)->sum('sks_substansi_total');
            $sksMatakuliah = $response->json('sks_matakuliah') ?? 0;

            $isValid = $totalSks == $sksMatakuliah;
            $dropdown = $dropdownService->get('dosen_pengajar');

            return view('masterdata.kelaskuliah.partials.dosen-pengajar', [
                'dosen_pa' => $dosen_pa,
                'dosen' => $dropdown['dosen_pengajar'] ?? [],
                'totalSks' => $totalSks,
                'sksMatakuliah' => $sksMatakuliah,
                'isValid' => $isValid
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
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "dosen-pengajar-kelas/kelas/{$id_kelas_kuliah}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menambah data dosen pengajar kelas ke API',
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
                ->get($this->apiUrl . "dosen-pengajar-kelas/{$id}");

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
                ->put($this->apiUrl . "dosen-pengajar-kelas/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
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
                ->delete($this->apiUrl . "dosen-pengajar-kelas/{$id}");

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
