<?php

namespace App\Http\Controllers\Siakad\DOSEN_PA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardDosenPAController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dashboard/dosen-pa');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data dashboard dari API DOSEN PA');
            }

            $dashboard_dosen_pa = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return view('dashboard.dosen_pa', compact('dashboard_dosen_pa'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
