<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\DataTableResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PengumumanController extends Controller
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
            // Ambil data beasiswa dari API (tanpa paginate)
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'pengumuman');
            
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data pengumuman dari API');
            }

            $pengumuman = $response->json()['data'] ?? [];
            
            return view('admin.master.website.pengumuman.index', compact('pengumuman'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'pengumuman', $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke API',
                    'errors' => $response->json(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "pengumuman/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "pengumuman/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal memperbarui data di API',
                    'errors' => $response->json(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "pengumuman/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data di API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
