<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\DataTableResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GaleriController extends Controller
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
            // Ambil data beasiswa dari API (tanpa paginate)
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'galeri');
            
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data galeri dari API');
            }

            $galeri = $response->json()['data'] ?? [];
            
            return view('admin.master.website.galeri.index', compact('galeri'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'judul' => 'required|string|max:255',
                'kategori' => 'required|string|max:100',
                'gambar' => 'required|file|max:2048',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['judul', 'kategori', 'deskripsi', 'tanggal']);

            // Jika ada file gambar, siapkan untuk multipart/form-data
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('gambar', file_get_contents($request->file('gambar')), $request->file('gambar')->getClientOriginalName())
                    ->post($this->apiUrl . 'galeri', $data);
            } else {
                // Jika tidak ada file, kirim sebagai JSON biasa
                $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'galeri', $data);
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "galeri/{$id}");

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
                'judul' => 'required|string|max:255',
                'kategori' => 'required|string|max:100',
                'gambar' => 'nullable|file|max:2048',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['judul', 'deskripsi', 'kategori', 'tanggal']);
            // Handle file upload dengan attach jika ada file
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('gambar', file_get_contents($request->file('gambar')), $request->file('gambar')->getClientOriginalName())
                    ->post($this->apiUrl . "galeri/{$id}?_method=PUT", $data);
            } else {
                $response = Http::withToken($this->apiToken)->put($this->apiUrl . "galeri/{$id}", $data);
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "galeri/{$id}");

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
