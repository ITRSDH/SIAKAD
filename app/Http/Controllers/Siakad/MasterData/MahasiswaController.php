<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class MahasiswaController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'mahasiswa');

            if (!$response->successful()) {
                return response()->json($response->json());
            }

            $apiData = $response->json()['data'] ?? [];

            $mahasiswa      = $apiData['mahasiswa'] ?? [];
            $prodi          = $apiData['prodi'] ?? [];
            $dosen          = $apiData['dosen'] ?? [];

            return view('masterdata.mahasiswa.index', compact(
                'mahasiswa',
                'prodi',
                'dosen'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'mahasiswa', $request->all());

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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "mahasiswa/{$id}");

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
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "mahasiswa/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "mahasiswa/{$id}");

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

    /**
     * Download template Excel untuk import mahasiswa
     */
    public function exportTemplate(Request $request, $id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "mahasiswa/template/{$id_prodi}");

            if ($response->successful()) {
                // Get the file content from API response
                $fileContent = $response->body();
                $filename = 'template_mahasiswa_import_' . date('Y-m-d_H-i-s') . '.xlsx';

                return response($fileContent)
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
                    ->header('Cache-Control', 'no-cache, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }

            return back()->with('error', 'Gagal download template');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal download template: ' . $e->getMessage());
        }
    }

    /**
     * Import mahasiswa dari Excel
     */
    public function import(Request $request, $id_prodi)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:10240', // max 10MB
            ], [
                'file.required' => 'File import wajib diisi',
                'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv',
                'file.max' => 'Ukuran file maksimal 10MB',
            ]);

            $file = $request->file('file');

            // Prepare file for upload
            $response = Http::withToken($this->apiToken)
                ->attach('file', fopen($file->getPathname(), 'r'), $file->getClientOriginalName())
                ->post($this->apiUrl . "mahasiswa/import/prodi/{$id_prodi}");

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil diimport',
                    'data' => $data
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal import data',
                'errors' => $response->json('errors') ?? []
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }
}
