<?php

namespace App\Http\Controllers\Siakad\MasterData\Capaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MappingPLCPLController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(Request $request, $id_prodi)
    {
        return view('masterdata.data_capaian.pemetaan_pl_cpl.index', compact('id_prodi'));
    }

    public function getData(Request $request, $id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "pemetaan-plcpl/{$id_prodi}", $request->all());

            if ($response->successful()) {

                $data = $response->json();

                return response()->json([
                    'data' => $data['data'] ?? []
                ]);
            }

            return response()->json([
                'data' => [],
                'message' => 'Gagal mengambil data di API'
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "pemetaan-plcpl", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mapping PL → CPL berhasil disimpan',
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
