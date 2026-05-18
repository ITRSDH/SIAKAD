<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DosenController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data dosen dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $prodi          = $apiData['prodi'] ?? [];
            $dosen          = $apiData['dosen'] ?? [];

            return view('masterdata.dosen.index', compact(
                'prodi',
                'dosen'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'dosen', $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
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

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "dosen/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "dosen/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data di API',
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "dosen/{$id}");

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
