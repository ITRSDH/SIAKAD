<?php

namespace App\Http\Controllers\Siakad\DOSEN_PENGAMPU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardDosenPengampuController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dashboard/dosen-pengampu');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data dashboard dari API DOSEN PENGAAMPU');
            }

            $dashboard_dosen_pengampu = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return view('dashboard.dosen_pengampu', compact('dashboard_dosen_pengampu'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
