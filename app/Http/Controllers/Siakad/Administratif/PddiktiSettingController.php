<?php

namespace App\Http\Controllers\Siakad\Administratif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PddiktiSettingController extends Controller
{
    protected string $apiUrl;

    protected ?string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl.'pddikti/setting');
            $setting = $response->successful() ? ($response->json('data') ?? []) : [];

            return view('administratif.pddikti.index', compact('setting'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat pengaturan PDDikti: '.$e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $payload = [
                'sync_enabled' => $request->boolean('sync_enabled'),
                'sync_mode' => $request->input('sync_mode', 'manual'),
                'auto_sync_mahasiswa' => $request->boolean('auto_sync_mahasiswa'),
                'auto_sync_krs' => $request->boolean('auto_sync_krs'),
                'auto_sync_nilai' => $request->boolean('auto_sync_nilai'),
                'feeder_url' => $request->input('feeder_url'),
                'feeder_username' => $request->input('feeder_username'),
            ];

            if ($request->filled('feeder_password')) {
                $payload['feeder_password'] = $request->input('feeder_password');
            }

            $response = Http::withToken($this->apiToken)->post($this->apiUrl.'pddikti/setting', $payload);

            if ($response->successful()) {
                return back()->with('success', 'Pengaturan integrasi Neo Feeder PDDikti berhasil disimpan.');
            }

            return back()->with('error', $response->json('message') ?? 'Gagal menyimpan pengaturan ke API.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function testConnection()
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl.'pddikti/test-connection');

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
