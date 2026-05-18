<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RuangKuliahController extends Controller
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
            // Ambil data ruang kuliah dari API (tanpa paginate)
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'ruang-kuliah');
            
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data ruang kuliah dari API');
            }

            $ruangKuliah = $response->json()['data'] ?? [];
            
            return view('masterdata.ruang_kuliah.index', compact('ruangKuliah'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('masterdata.ruang_kuliah.create');
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'ruang-kuliah', $request->all());

            if ($response->successful()) {
                return redirect()->route('ruang-kuliah.index')->with('success', 'Data ruang kuliah berhasil ditambahkan');
            }

            return back()->with('error', 'Gagal menyimpan data ke API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "ruang-kuliah/{$id}");

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

    public function edit($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "ruang-kuliah/{$id}");

            if ($response->successful()) {
                $ruangKuliah = $response->json()['data'] ?? [];
                return view('masterdata.ruang_kuliah.edit', compact('ruangKuliah'));
            }

            return back()->with('error', 'Gagal mengambil data dari API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "ruang-kuliah/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('ruang-kuliah.index')->with('success', 'Data ruang kuliah berhasil diperbarui');
            }

            return back()->with('error', 'Gagal memperbarui data di API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "ruang-kuliah/{$id}");

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
