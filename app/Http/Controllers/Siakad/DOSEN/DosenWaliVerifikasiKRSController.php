<?php

namespace App\Http\Controllers\Siakad\DOSEN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DosenWaliVerifikasiKRSController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function daftarKrsPerluVerifikasi()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen/verifikasi-krs/daftar-verifikasi');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data mahasiswa dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $krs_list     = $apiData['krs_list'] ?? [];
            $total_krs    = $apiData['total_krs'] ?? [];

            // dd($apiData);

            return view('dosen.dosenwali.krs-verifikasi', compact(
                'krs_list',
                'total_krs',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function detailKrs($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "dosen/verifikasi-krs/{$id}");

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data KRS dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // dd($apiData);

            return view('dosen.dosenwali.detail-verifikasi-krs', [
                'detail_krs' => $apiData,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function approveKrs(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . "dosen/verifikasi-krs/{$id}/approve", $request->all());

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

    public function rejectKrs(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . "dosen/verifikasi-krs/{$id}/reject", $request->all());

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

    public function daftarKrsTerverifikasi()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen/verifikasi-krs/daftar-terverifikasi');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data mahasiswa dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $krs_list     = $apiData['krs_list'] ?? [];
            $total_krs    = $apiData['total_krs'] ?? [];

            // dd($apiData);

            return view('dosen.dosenwali.krs-terverifikasi', compact(
                'krs_list',
                'total_krs',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
