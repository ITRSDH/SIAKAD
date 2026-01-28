<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PrestasiController extends Controller
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
            $prodiResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'prodi');

            if (!$prodiResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data dari API');
            }

            $prodiData = $prodiResponse->json()['data'] ?? [];
            $prodi = $prodiData['prodi'] ?? [];
            $jenjangPendidikan = $prodiData['jenjang_pendidikan'] ?? [];

            $prestasiResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'prestasi');

            if (!$prestasiResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data prestasi dari API');
            }

            $prestasi = $prestasiResponse->json()['data'] ?? [];

            return view('admin.master.website.prestasi.index', compact('prestasi', 'prodi', 'jenjangPendidikan'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_mahasiswa' => 'required|string|max:255',
                'id_prodi' => 'required|string|max:50',
                'judul_prestasi' => 'required|string|max:255',
                'tingkat' => 'required|in:kampus,nasional,internasional',
                'tahun' => 'required|integer|min:1900|max:2100',
                'deskripsi' => 'required|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['nama_mahasiswa', 'id_prodi', 'judul_prestasi', 'tingkat', 'tahun', 'deskripsi']);
            
            // Jika ada file gambar, siapkan untuk multipart/form-data
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('gambar', file_get_contents($request->file('gambar')), $request->file('gambar')->getClientOriginalName())
                    ->post($this->apiUrl . 'prestasi', $data);
            } else {
                // Jika tidak ada file, kirim sebagai JSON biasa
                $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'prestasi', $data);
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "prestasi/{$id}");

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
            // Handle file upload dengan attach jika ada file
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('gambar', file_get_contents($request->file('gambar')), $request->file('gambar')->getClientOriginalName())
                    ->post($this->apiUrl . "prestasi/{$id}?_method=PUT", $request->except('gambar'));
            } else {
                $response = Http::withToken($this->apiToken)->put($this->apiUrl . "prestasi/{$id}", $request->all());
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "prestasi/{$id}");

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
