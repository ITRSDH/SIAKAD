<?php

namespace App\Http\Controllers\Siakad\MAHASISWA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PengajuanKRSController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function daftarMatkulPilihan()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'pengajuan-krs/daftar-matkul');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data mahasiswa dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $semester_aktif             = $apiData['semester_aktif'] ?? [];
            $kurikulum                  = $apiData['kurikulum'] ?? [];
            // $krs                        = $apiData['krs'] ?? [];
            $jumlah_matkul_tersedia     = $apiData['jumlah_matkul_tersedia'] ?? 0;
            $matkul_pilihan             = $apiData['matkul_pilihan'] ?? [];

            // dd($apiData);

            return view('mahasiswa.pengajuankrs.daftar-matkul', compact(
                'semester_aktif',
                'kurikulum',
                // 'krs',
                'jumlah_matkul_tersedia',
                'matkul_pilihan'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pengajuanKrs(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'pengajuan-krs', $request->all());

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

    public function simpanDraftKrs(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'draft', $request->all());

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

    public function submitKrs(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . "{$id}/submit", $request->all());
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

    public function batalPengajuanKrs(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "pengajuan-krs/{$id}", $request->all());
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

    public function statusPengajuanKrs()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'pengajuan-krs/status');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data mahasiswa dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // dd($apiData);

            return view('mahasiswa.pengajuankrs.status-krs', [
                'krs' => $apiData
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
