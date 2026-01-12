<?php

namespace App\Http\Controllers\Siakad\KAPRODI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardKaprodiController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dashboard/kaprodi');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data dashboard dari API KAPRODI');
            }

            $dashboard_kaprodi = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return view('dashboard.kaprodi', compact('dashboard_kaprodi'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
