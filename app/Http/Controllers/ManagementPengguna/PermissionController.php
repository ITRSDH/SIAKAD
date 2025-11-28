<?php

namespace App\Http\Controllers\ManagementPengguna;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PermissionController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    /**
     * Tampilkan daftar permission dari API.
     */
    public function index()
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . 'permissions');

            if ($response->successful()) {
                // Jika API mengembalikan "data", gunakan itu; jika tidak, langsung gunakan body
                $permissions = $response->json()['data'] ?? $response->json();

                return view('admin.master.pengguna.setting-user.permission.index', compact('permissions'));
            }

            return back()->with('error', 'Gagal mengambil data permission dari API');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Sinkronkan permission dengan daftar route (memanggil endpoint API /permissions/sync).
     */
    public function sync()
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'permissions/sync');

            if ($response->successful()) {
                $result = $response->json();
                return back()->with('success', "Sinkronisasi berhasil. Tambah: {$result['added']}, Hapus: {$result['removed']}");
            }

            return back()->with('error', 'Gagal melakukan sinkronisasi permission');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus permission melalui API.
     */
    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "permissions/{$id}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permission berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permission',
                'errors'  => $response->json()
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
