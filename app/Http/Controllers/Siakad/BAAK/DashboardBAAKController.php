<?php

namespace App\Http\Controllers\Siakad\BAAK;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardBAAKController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dashboard/baak');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data dashboard dari API BAAK');
            }

            $dashboard_baak = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return view('dashboard.baak', compact('dashboard_baak'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
