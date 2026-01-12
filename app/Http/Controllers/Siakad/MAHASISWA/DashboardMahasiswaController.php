<?php

namespace App\Http\Controllers\Siakad\MAHASISWA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardMahasiswaController extends Controller
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
            // Ambil data kurikulum dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dashboard/mahasiswa');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data dashboard dari API MAHASISWA');
            }

            $dashboard_mahasiswa = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return view('dashboard.mahasiswa', compact('dashboard_mahasiswa'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
