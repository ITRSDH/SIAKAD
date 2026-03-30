<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SertifikatAkreditasi extends Controller
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
            // Ambil data sertifikat akreditasi
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'sertifikat-akreditasi');

            if ($response->successful()) {
                $sertifikatAkreditasi = $response->json()['data'] ?? [];

                return view('admin.master.website.sertifikat_akreditasi.index', compact('sertifikatAkreditasi'));
            }

            return back()->with('error', 'Gagal mengambil data dari API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto_sertifikat' => 'nullable|file|max:2048',
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['nama', 'deskripsi']);

            // Jika ada file gambar, siapkan untuk multipart/form-data
            if ($request->hasFile('foto_sertifikat')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('foto_sertifikat', file_get_contents($request->file('foto_sertifikat')), $request->file('foto_sertifikat')->getClientOriginalName())
                    ->post($this->apiUrl . 'sertifikat-akreditasi', $data);
            } else {
                // Jika tidak ada file, kirim sebagai JSON biasa
                $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'sertifikat-akreditasi', $data);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke API',
                    'errors' => $response->json(),
                ],
                422,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "sertifikat-akreditasi/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto_sertifikat' => 'nullable|file|max:2048',
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['nama', 'deskripsi']);

            // Handle file upload dengan attach jika ada file
            if ($request->hasFile('foto_sertifikat')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('foto_sertifikat', file_get_contents($request->file('foto_sertifikat')), $request->file('foto_sertifikat')->getClientOriginalName())
                    ->post($this->apiUrl . "sertifikat-akreditasi/{$id}?_method=PUT", $data);
            } else {
                $response = Http::withToken($this->apiToken)->put($this->apiUrl . "sertifikat-akreditasi/{$id}", $data);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal memperbarui data di API',
                    'errors' => $response->json(),
                ],
                422,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "sertifikat-akreditasi/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data di API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
