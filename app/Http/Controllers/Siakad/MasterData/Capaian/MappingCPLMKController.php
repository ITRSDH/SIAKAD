<?php

namespace App\Http\Controllers\Siakad\MasterData\Capaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MappingCPLMKController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    // ✅ Load halaman saja
    public function index(Request $request, $id_prodi)
    {
        return view('masterdata.data_capaian.pemetaan_cpl_mk.index', compact('id_prodi'));
    }

    // ✅ AJAX ambil data dari API
    public function getData(Request $request, $id_prodi)
    {
        try {
            $levelPemetaan = $request->get('level_pemetaan', 'cpl');

            $response = Http::withToken($this->apiToken)
                ->get("{$this->apiUrl}pemetaan-cplmk/{$id_prodi}", [
                    'level_pemetaan' => $levelPemetaan
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal mengambil data di API'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "pemetaan-cplmk", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mapping CPL → MK berhasil disimpan',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data',
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
