<?php

namespace App\Http\Controllers\Siakad\MasterData\Capaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileLulusanController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getData(Request $request, $id_prodi)
    {
        try {

            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "profile-lulusan/prodi/{$id_prodi}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data di API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request, $id_prodi)
    {
        try {
            // Panggil getData
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "prodi/{$id_prodi}");

            // Ambil data
            $prodi = $response->json()['data'] ?? [];
            // dd($prodi);

            return view('masterdata.data_capaian.pl.index', compact(
                'id_prodi',
                'prodi',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode_pl' => 'required|string|max:50',
                'profil_lulusan' => 'required|string',
                'deskripsi_id' => 'required|string',
                'deskripsi_en' => 'nullable|string',
                'profesi_lulusan' => 'nullable|string',
                'status' => 'required|in:aktif,tidak_aktif',
                'id_prodi' => 'required|integer'
            ]);

            // Combine deskripsi if needed
            $deskripsi = $request->deskripsi_id;
            if ($request->deskripsi_en) {
                $deskripsi .= ' | EN: ' . $request->deskripsi_en;
            }

            $data = [
                'kode_pl' => $request->kode_pl,
                'profil_lulusan' => $request->profil_lulusan,
                'deskripsi' => $deskripsi,
                'profesi_lulusan' => $request->profesi_lulusan,
                'status' => $request->status,
                'id_prodi' => $request->id_prodi
            ];

            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "profile-lulusan", $data);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile lulusan berhasil ditambahkan',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan profile lulusan',
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
