<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\DropdownService;

class KelaskuliahController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getDatakelaskuliah()
    {
        try {
            // Ambil data kelaskuliah dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-kuliah');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data All Kelas Kuliah dari API');
            }

            $kelaskuliah = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return response()->json(['data' => $kelaskuliah]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        return view('masterdata.kelaskuliah.index');
    }

    public function create(DropdownService $dropdownService)
    {
        try {

            $dropdown = $dropdownService->get('prodi,semester,kurikulum_matakuliah');

            return view('masterdata.kelaskuliah.create', [
                'prodi' => $dropdown['prodi'] ?? [],
                'semester' => $dropdown['semester'] ?? [],
                'kurikulum_matakuliah' => $dropdown['kurikulum_matakuliah'] ?? []
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function detail(DropdownService $dropdownService, $id)
    {
        try {
            // Ambil data kelas-kuliah dari API
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kelas-kuliah/{$id}");

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengambil data dari API');
            }

            $kelaskuliah = $response->json('data');

            $dropdown = $dropdownService->get('prodi,semester,kurikulum_matakuliah');
            return view('masterdata.kelaskuliah.detail', [
                'kelaskuliah' => $kelaskuliah,
                'prodi' => $dropdown['prodi'] ?? [],
                'semester' => $dropdown['semester'] ?? [],
                'kurikulum_matakuliah' => $dropdown['kurikulum_matakuliah'] ?? []
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'kelas-kuliah', $request->all());

            if ($response->successful()) {

                // Ambil ID dari response API
                $id = $response->json('data.id');

                return redirect()
                    ->route('kelas-kuliah.detail', $id)
                    ->with('success', 'Data berhasil disimpan');
            }

            return back()->withErrors(
                $response->json('message') ?? 'Gagal menyimpan data'
            );
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "kelas-kuliah/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()
                    ->route('kelas-kuliah.detail', $id)
                    ->with('success', 'Data berhasil diubah');
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data di API',
                'errors' => $response->json()
            ], 404);
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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "kelas-kuliah/{$id}");

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
