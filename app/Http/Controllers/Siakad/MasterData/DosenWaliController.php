<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DosenWaliController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getDataDosenWali()
    {
        try {
            // Ambil data kelaskuliah dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen-wali');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data All Dosen Wali dari API');
            }

            $dosenwali = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return response()->json(['data' => $dosenwali]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function index(?string $pageTitle = null, ?string $pageDescription = null)
    {
        return view('masterdata.dosen_wali.index', [
            'pageTitle' => $pageTitle ?? 'Dosen Wali Management',
            'pageHeading' => $pageTitle ?? 'Dosen Wali Management',
            'pageDescription' => $pageDescription,
            'pageRoute' => request()->routeIs('aktor-akademik.*') ? route('aktor-akademik.pembimbing-akademik') : route('dosen-wali.index'),
            'pageListLabel' => $pageTitle ? "List {$pageTitle}" : 'List Dosen Wali',
            'createRoute' => route('dosen-wali.create'),
        ]);
    }

    public function searchMahasiswa(Request $request)
    {
        try {
            // 🔥 Ambil parameter sesuai API baru
            $params = array_filter([
                'nama'     => $request->get('nama'), // gabungan nim + nama
                'angkatan' => $request->get('angkatan'),
                'id_prodi' => $request->get('id_prodi'),
                'page'     => $request->get('page', 1),
                'per_page' => $request->get('per_page', 10),
            ], fn($v) => !is_null($v) && $v !== '');

            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . 'dosen-wali/mahasiswa', $params);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data mahasiswa dari API'
                ], 500);
            }

            $responseData = $response->json();

            return response()->json([
                'success' => true,
                'data' => $responseData['data'] ?? [],
                'meta' => $responseData['meta'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(DropdownService $dropdownService)
    {
        try {
            $dropdown = $dropdownService->get('dosen_wali,prodi');

            return view('masterdata.dosen_wali.create', [
                'dosen_wali' => $dropdown['dosen_wali'] ?? [],
                'prodi' => $dropdown['prodi'] ?? []
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'dosen-wali/assign', $request->all());

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

    public function unassign(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'dosen-wali/unassign', $request->all());

            if ($response->successful()) {
                $responseData = $response->json();

                // Get the new dosen wali ID for redirect
                $newDosenId = $request->input('id_dosen_baru');

                return response()->json([
                    'success' => true,
                    'message' => 'Dosen wali berhasil diperbarui',
                    'data' => $responseData['data'] ?? [],
                    'redirect_url' => route('dosen-wali.detail', $newDosenId)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui dosen wali',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detail(DropdownService $dropdownService, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "dosen-wali/{$id}");

            if (!$response->successful()) {
                // Return JSON error for AJAX requests
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengambil data dari API'
                    ], 500);
                }
                return back()->withErrors('Gagal mengambil data dari API');
            }

            $responseData = $response->json();

            // Return JSON for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $responseData['data'] ?? null
                ]);
            }

            // Return view for regular requests
            $dropdown = $dropdownService->get('dosen_wali,prodi');

            return view('masterdata.dosen_wali.detail', [
                'dosenWali' => $responseData['data'] ?? null,
                'dosen_wali' => $dropdown['dosen_wali'] ?? [],
                'prodi' => $dropdown['prodi'] ?? []
            ]);
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }


    public function remove(Request $request)
    {
        try {
            // Convert id_mahasiswa to mahasiswa_ids array for API compatibility
            $data = $request->all();
            if (isset($data['id_mahasiswa']) && !isset($data['mahasiswa_ids'])) {
                $data['mahasiswa_ids'] = is_array($data['id_mahasiswa'])
                    ? $data['id_mahasiswa']
                    : [$data['id_mahasiswa']];
                unset($data['id_mahasiswa']);
            }

            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'dosen-wali/remove', $data);

            if ($response->successful()) {
                $responseData = $response->json();

                return response()->json([
                    'success' => true,
                    'message' => 'Mahasiswa berhasil dihapus dari daftar bimbingan',
                    'data' => $responseData['data'] ?? [],
                    'refresh_needed' => true
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data dari API',
                'errors' => $response->json()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
