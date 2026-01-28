<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DosenMKController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen-mk');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data Dosen Mata Kuliah dari API');
            }

            $dosen = $response->json()['data'] ?? [];

            return view('masterdata.dosen_mk.index', compact(
                'dosen'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'dosen-mk/create');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data Dosen Dan Kelas Mata Kuliah dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $dosen          = $apiData['dosen'] ?? [];
            $kelasmk          = $apiData['kelasmk'] ?? [];

            return view('masterdata.dosen_mk.create', compact(
                'dosen',
                'kelasmk'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'dosen-mk', $request->all());

            if ($response->successful()) {
                return $response->successful()
                    ? redirect()->route('dosen-mk.index')->with('success', 'Data berhasil disimpan')
                    : back()->withErrors($response->json('message'));
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "dosen-mk/{$id}");
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "dosen-mk/{$id}/edit");
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data Dosen MK dari API');
            }

            $apiData = $response->json()['data'] ?? [];

            $dosen_mk    = $apiData['dosen_mk'] ?? [];
            $dosen      = $apiData['dosen'] ?? [];
            $kelasmk    = $apiData['kelasmk'] ?? [];

            return view('masterdata.dosen_mk.edit', compact(
                'dosen_mk',
                'dosen',
                'kelasmk'
            ));
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
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "dosen-mk/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('dosen-mk.index')->with('success', 'Dosen Mata Kuliah berhasil diperbarui.');
            }

            return back()->with('error', 'Gagal memperbarui data di API')
                ->withErrors($response->json())
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "dosen-mk/{$id}");

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
