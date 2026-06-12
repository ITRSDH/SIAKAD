<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KurikulumMahasiswaController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'mahasiswa');

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal mengambil data mahasiswa dari API.');
            }

            $apiData = $response->json('data', []);

            return view('akademik.kurikulum_mahasiswa.index', [
                'mahasiswa' => $apiData['mahasiswa'] ?? [],
                'prodi' => $apiData['prodi'] ?? [],
                'kurikulum' => $apiData['kurikulum'] ?? [],
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function riwayatKurikulum(string $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "mahasiswa/{$id}/riwayat-kurikulum");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal mengambil riwayat kurikulum dari API.',
                'errors' => $response->json('errors') ?? [],
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function migrateKurikulum(Request $request, string $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "mahasiswa/{$id}/migrasi-kurikulum", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal memproses migrasi kurikulum di API.',
                'errors' => $response->json('errors') ?? [],
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
