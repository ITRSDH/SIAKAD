<?php

namespace App\Http\Controllers\ManagementPengguna;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'users');

            if ($response->successful()) {
                $apiData = $response->json()['data'] ?? [];

                // Ekstrak data
                $users = $apiData['users'] ?? [];
                $roles = $apiData['role'] ?? [];
                return view('admin.master.pengguna.setting-user.user.index', compact('users', 'roles'));
            }

            return back()->with('error', 'Gagal mengambil data dari API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'users', $request->all());

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

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "users/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            // 1. Update user ke API
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "users/{$id}", $request->all());

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data di API',
                    'errors'  => $response->json()
                ], 422);
            }

            // 2. Ambil data user terbaru dari /auth/me
            $userResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "auth/me");

            if ($userResponse->successful()) {

                $apiUser = $userResponse->json()['user'];

                // 3. Update seluruh data session user
                // Ambil session lama
                $current = Session::get('user', []);

                // Timpa data user lama dengan yang baru
                $updatedUser = array_merge($current, [
                    'id'         => $apiUser['id'] ?? $current['id'] ?? null,
                    'name'       => $apiUser['name'] ?? $current['name'] ?? null,
                    'email'      => $apiUser['email'] ?? $current['email'] ?? null,
                    'status'     => $apiUser['status'] ?? $current['status'] ?? null,
                    'role'       => $apiUser['role'] ?? $current['role'] ?? [],
                    'permission' => $apiUser['permission'] ?? $current['permission'] ?? [],
                ]);

                // 4. Simpan ke session
                Session::put('user', $updatedUser);
            }

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'data'    => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "users/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data di API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
