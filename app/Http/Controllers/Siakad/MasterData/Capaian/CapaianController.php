<?php

namespace App\Http\Controllers\Siakad\MasterData\Capaian;

use App\Http\Controllers\Controller;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CapaianController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getDataProdi(DropdownService $dropdownService)
    {
        try {
            // Ambil data mata kuliah terkelompok dari API
            $prodi = $dropdownService->get('prodi');
            // Kirim data ke view
            return response()->json(['data' => $prodi['prodi']]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function indexProdi(Request $request)
    {
        return view('masterdata.data_capaian.indexProdi');
    }

    public function detailProdi(Request $request, $id_prodi)
    {
        try {
            // Ambil data prodi dari API
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "prodi/{$id_prodi}");

            // Ambil data
            $prodi = $response->json()['data'] ?? [];

            return view('masterdata.data_capaian.detail', compact(
                'id_prodi',
                'prodi',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
