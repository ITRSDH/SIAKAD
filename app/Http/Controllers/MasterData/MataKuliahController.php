<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MataKuliahController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(Request $request)
    {
        try {
            // Ambil semua data master dalam satu API call
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'all-mata-kuliah');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data master dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // Ekstrak data
            $mk = $apiData['mata-kuliah'] ?? [];
            $kurikulum = $apiData['kurikulum'] ?? [];
            $prodi = $apiData['prodi'] ?? [];

            // Kirim data ke view
            return view('admin.master.akademik.matakuliah.index', compact('mk', 'kurikulum', 'prodi'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Sisanya dari controller tetap sama...
    public function create()
    {
        try {
            // Ambil semua data master dalam satu API call
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'all-mata-kuliah');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data master dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // Ekstrak data
            $mk = $apiData['mata-kuliah'] ?? [];
            $kurikulum = $apiData['kurikulum'] ?? [];
            $prodi = $apiData['prodi'] ?? [];

            return view('admin.master.akademik.matakuliah.create', compact('prodi', 'kurikulum'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'mata-kuliah', $request->all());

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

    public function edit($id)
    {
        try {
            // Panggil endpoint baru di API untuk mendapatkan data MK dan data master terkait
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "edit-mata-kuliah/{$id}");

            if (!$response->successful()) {
                $errorData = $response->json();
                return back()->with('error', $errorData['message'] ?? 'Gagal mengambil data mata kuliah dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // Ekstrak data dari respons API
            $matakuliah = $apiData['mata-kuliah'] ?? null;
            $prodi = $apiData['prodi'] ?? [];
            $kurikulum = $apiData['kurikulum'] ?? [];
            $selectedProdiId = $apiData['selected_prodi_id'] ?? null;
            $selectedKurikulumId = $apiData['selected_kurikulum_id'] ?? null;

            // Validasi apakah data MK ditemukan
            if (!$matakuliah) {
                return back()->with('error', 'Data mata kuliah tidak ditemukan.');
            }

            // Kirim data ke view edit
            // Kita kirim semua data yang dibutuhkan oleh view dan JavaScript
            return view('admin.master.akademik.matakuliah.edit', compact(
                'matakuliah',
                'prodi',
                'kurikulum',
                'selectedProdiId',
                'selectedKurikulumId'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "mata-kuliah/{$id}");

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
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "mata-kuliah/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data ke API',
                'errors' => $response->json()
            ], 422);
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "mata-kuliah/{$id}");

            if ($response->successful()) {
                $responseData = $response->json();
                if (request()->ajax()) {
                    return response()->json($responseData);
                }
                return redirect()->route('matakuliah.index')->with('success', $responseData['message'] ?? 'Data berhasil dihapus.');
            }

            $errors = $response->json();
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errors['message'] ?? 'Gagal menghapus data dari API',
                ], 404);
            }
            return back()->with('error', $errors['message'] ?? 'Gagal menghapus data.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }
}
