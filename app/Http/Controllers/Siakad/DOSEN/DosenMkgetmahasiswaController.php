<?php

namespace App\Http\Controllers\Siakad\DOSEN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DosenMkgetmahasiswaController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getmahasiswa()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosenmk/mahasiswa');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data mahasiswa dari API');
            }

            // data = array of kelas_mk
            $kelasList = $response->json('data') ?? [];
            // dd($apiData);

            return view('dosen.dosenmk.daftar-mahasiswa', compact(
                'kelasList',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function storeNilai(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'dosenmk/nilai', $request->all());

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
}
