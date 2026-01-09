<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            // Ambil data mata kuliah terkelompok dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'mata-kuliah');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data master dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // Ekstrak data
            $groupedMataKuliah = $apiData['grouped_mata_kuliah'] ?? [];

            // Kirim data ke view
            return view('masterdata.mata_kuliah.index', compact('groupedMataKuliah'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Ambil data master untuk membuat mata kuliah
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'mata-kuliah/create');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data master dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // Ekstrak data
            $prodi = $apiData['prodi'] ?? [];
            $kurikulum = $apiData['kurikulum'] ?? [];

            return view('masterdata.mata_kuliah.create', compact('prodi', 'kurikulum'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'mata-kuliah', $request->all());

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
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/{$id}");

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

    // Panggil endpoint edit dari API
    public function edit($semester, Request $request)
    {
        try {
            $idKurikulum = $request->query('id_kurikulum');

            if (!$idKurikulum) {
                return back()->with('error', 'ID Kurikulum diperlukan.');
            }

            // Panggil API untuk mengambil data semester
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/semester/{$semester}?id_kurikulum={$idKurikulum}");

            if (!$response->successful()) {
                $errorData = $response->json();
                return back()->with('error', $errorData['message'] ?? 'Gagal mengambil data semester dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            // Ekstrak data dari respons API
            $mataKuliah = $apiData['mata_kuliah'] ?? [];
            $prodi = $apiData['prodi'] ?? [];
            $kurikulum = $apiData['kurikulum'] ?? [];
            $selectedProdi = $apiData['selected_prodi'] ?? null;
            $selectedKurikulum = $apiData['selected_kurikulum'] ?? $idKurikulum;

            return view('masterdata.mata_kuliah.edit-semester', compact(
                'mataKuliah',
                'prodi',
                'kurikulum',
                'selectedProdi',
                'selectedKurikulum',
                'semester'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Panggil endpoint update dari API
    public function update(Request $request, $semester)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "mata-kuliah/semester/{$semester}", $request->all());

            if ($response->successful()) {
                $responseData = $response->json();
                if (request()->ajax()) {
                    return response()->json($responseData);
                }
                return redirect()->route('mata-kuliah.index')
                    ->with('success', $responseData['message'] ?? 'Data semester berhasil diperbarui.');
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

    // Panggil endpoint destroy semester dari API
    public function destroy(Request $request, $semester)
    {
        try {
            $idKurikulum = $request->query('id_kurikulum');

            if (!$idKurikulum) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Kurikulum diperlukan.'
                ], 422);
            }

            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "mata-kuliah/semester/{$semester}?id_kurikulum={$idKurikulum}");

            if ($response->successful()) {
                $responseData = $response->json();
                if (request()->ajax()) {
                    return response()->json($responseData);
                }
                return redirect()->route('mata-kuliah.index')
                    ->with('success', $responseData['message'] ?? 'Data semester berhasil dihapus.');
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

    // Tambahkan method baru di controller frontend
    public function destroySingle($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "mata-kuliah/{$id}");

            if ($response->successful()) {
                $responseData = $response->json();
                if (request()->ajax()) {
                    return response()->json($responseData);
                }
                return redirect()->route('mata-kuliah.index')
                    ->with('success', $responseData['message'] ?? 'Data berhasil dihapus.');
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
