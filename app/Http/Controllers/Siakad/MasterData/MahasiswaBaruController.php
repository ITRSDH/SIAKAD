<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MahasiswaBaruController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'mahasiswa-baru');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data mahasiswa baru dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $mahasiswa      = $apiData['mahasiswa'] ?? [];
            $prodi          = $apiData['prodi'] ?? [];

            return view('masterdata.mahasiswa-baru.index', compact(
                'mahasiswa',
                'prodi',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function sync(Request $request)
    {
        try {
            $request->validate([
                'id_periode_pendaftaran' => 'required|string'
            ]);

            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'mahasiswa-baru/sync', [
                    'id_periode_pendaftaran' => $request->id_periode_pendaftaran
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal melakukan sync ke API'
                ], 500);
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'mahasiswa-baru', $request->all());

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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "mahasiswa-baru/{$id}");

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
            $validated = $request->validate([
                'nama_mahasiswa' => 'required|string|max:255',
                'nim' => 'required|string|max:55',
                'id_prodi' => 'required|string',
                'jenis_kelamin' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string|max:20',
                'asal_sekolah' => 'nullable|string|max:255',
                'nama_orang_tua' => 'nullable|string|max:255',
                'no_hp_orang_tua' => 'nullable|string|max:20',
                'status' => 'required|in:Aktif,Cuti,DO,Lulus,PMB',
                'angkatan' => 'required|integer|min:1990|max:' . (date('Y') + 10),
            ]);

            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "mahasiswa-baru/{$id}", $validated);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // Debug: log response dari API
            Log::error('Update Mahasiswa API Error: ' . $response->body());
            Log::error('Payload sent: ' . json_encode($validated));

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data di API',
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "mahasiswa-baru/{$id}");

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
