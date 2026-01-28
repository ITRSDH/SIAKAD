<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class JadwalBebanAjarDosenController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index($dosenmk)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "jadwal-beban-ajar-dosen/{$dosenmk}");

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data Dosen Mata Kuliah dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $dosen_mk        = $apiData['dosen_mk'] ?? [];
            $ruangs          = $apiData['ruangs'] ?? [];
            $hari_options    = $apiData['hari_options'] ?? [];
            $jam_options     = $apiData['jam_options'] ?? [];
            $existing_jadwal = $apiData['existing_jadwal'] ?? [];

            return view('masterdata.jadwal_bebanajar.create', compact(
                'dosen_mk',
                'ruangs',
                'hari_options',
                'jam_options',
                'existing_jadwal'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "jadwal-beban-ajar-dosen", $request->all());

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Gagal menyimpan data jadwal';
                return back()->with('error', $errorMessage);
            }

            $responseData = $response->json();

            return redirect()
                ->route('jadwal-beban-ajar.index', $request->id_kelas_mk)
                ->with('success', $responseData['message']);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($dosenmk)
    {
        return $this->index($dosenmk);
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "jadwal-beban-ajar-dosen/{$id}");

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Gagal menghapus data jadwal';
                return back()->with('error', $errorMessage);
            }

            $responseData = $response->json();

            return back()->with('success', $responseData['message']);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
