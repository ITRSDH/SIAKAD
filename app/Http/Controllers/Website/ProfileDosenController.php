<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileDosenController extends Controller
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

            $profileDosenResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'profile-dosen');

            if (!$profileDosenResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data profile dosen dari API');
            }

            $profileDosenData = $profileDosenResponse->json()['data'] ?? [];
            $profileDosen = $profileDosenData['profile_dosen'] ?? $profileDosenData;

            return view('admin.master.website.profile_dosen.index', compact('profileDosen', 'prodi', 'jenjangPendidikan'));
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
                'id_prodi' => 'required|string|max:50',
                'nidn' => 'required|string|max:255',
                'status' => 'required|in:Aktif,Tidak Aktif',
                'biografi' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['nama', 'id_prodi', 'nidn', 'status', 'biografi']);
            
            // Jika ada file gambar, siapkan untuk multipart/form-data
            if ($request->hasFile('foto')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
                    ->post($this->apiUrl . 'profile-dosen', $data);
            } else {
                // Jika tidak ada file, kirim sebagai JSON biasa
                $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'profile-dosen', $data);
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "profile-dosen/{$id}");

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
            if ($request->hasFile('foto')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
                    ->post($this->apiUrl . "profile-dosen/{$id}?_method=PUT", $request->except('foto'));
            } else {
                $response = Http::withToken($this->apiToken)->put($this->apiUrl . "profile-dosen/{$id}", $request->all());
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "profile-dosen/{$id}");

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
