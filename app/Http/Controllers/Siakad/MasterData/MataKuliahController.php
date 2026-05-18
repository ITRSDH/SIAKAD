<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Services\DropdownService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MataKuliahController extends Controller
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
        return view('masterdata.mata_kuliah.indexProdi');
    }

    public function getData(Request $request, $id_prodi)
    {
        try {

            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/prodi/{$id_prodi}", $request->all());

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Gagal mengambil data dari API'
                ], 500);
            }

            // Langsung forward response apa adanya
            return response()->json(
                $response->json(),
                $response->status()
            );
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
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

            return view('masterdata.mata_kuliah.index', compact(
                'id_prodi',
                'prodi',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/{$id}");

            $matakuliah = $response->json()['data'] ?? [];

            // Ambil data prasyarat dengan error handling
            try {
                $prasyaratResponse = Http::withToken($this->apiToken)
                    ->timeout(30)
                    ->get($this->apiUrl . "mata-kuliah/{$id}/prasyarat");

                $prasyarat = $prasyaratResponse->successful()
                    ? ($prasyaratResponse->json()['data'] ?? [])
                    : [];

                if (!$prasyaratResponse->successful()) {
                    Log::warning('Gagal mengambil data prasyarat di detail page', [
                        'id_mata_kuliah' => $id,
                        'status' => $prasyaratResponse->status(),
                        'message' => $prasyaratResponse->json('message') ?? 'Unknown error'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception saat mengambil prasyarat di detail page', [
                    'id_mata_kuliah' => $id,
                    'message' => $e->getMessage()
                ]);
                $prasyarat = [];
            }

            $mataKuliahProdiResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/prodi/" . ($matakuliah['id_prodi'] ?? ''));

            $referensiPrasyarat = collect($mataKuliahProdiResponse->json()['data'] ?? [])
                ->filter(fn ($item) => (string) ($item['id'] ?? '') !== (string) $id)
                ->values()
                ->all();

            if ($response->successful()) {
                return view('masterdata.mata_kuliah.detail', compact('matakuliah', 'prasyarat', 'referensiPrasyarat'));
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function prasyarat($id)
    {
        $response = Http::withToken($this->apiToken)
            ->get($this->apiUrl . "mata-kuliah/{$id}/prasyarat");

        if ($response->successful()) {
            $responseData = $response->json();
            
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'data' => $responseData['data']['prasyarat'] ?? []
                ]);
            }
            
            return response()->json($responseData, $response->status());
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Gagal mengambil data prasyarat',
            'errors' => $response->json('errors') ?? []
        ], $response->status());
    }

    public function updatePrasyarat(Request $request, $id)
    {
        $prasyarat = $request->input('prasyarat', []);
        
        // Transform format data untuk backend
        $prasyaratData = [];
        foreach ($prasyarat as $item) {
            if (is_string($item)) {
                // Format lama: hanya ID
                $prasyaratData[] = [
                    'id_mata_kuliah_prasyarat' => $item,
                    'min_bobot_nilai' => 2.00
                ];
            } elseif (is_array($item)) {
                // Format baru: dengan min_bobot_nilai
                $prasyaratData[] = [
                    'id_mata_kuliah_prasyarat' => $item['id'],
                    'min_bobot_nilai' => floatval($item['min_bobot_nilai'] ?? 2.00)
                ];
            }
        }

        $response = Http::withToken($this->apiToken)
            ->put($this->apiUrl . "mata-kuliah/{$id}/prasyarat", [
                'prasyarat' => $prasyaratData,
            ]);

        if ($response->successful()) {
            $responseData = $response->json();
            
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'message' => $responseData['message'] ?? 'Prasyarat berhasil diperbarui'
                ]);
            }
            
            return response()->json($responseData, $response->status());
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Gagal menyimpan prasyarat',
            'errors' => $response->json('errors') ?? []
        ], $response->status());
    }

    public function create($id_prodi)
    {
        try {
            // Panggil getData
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "prodi/{$id_prodi}");

            // Ambil data
            $prodi = $response->json()['data'] ?? [];
            // dd($prodi);

            return view('masterdata.mata_kuliah.create', compact(
                'id_prodi',
                'prodi',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request, $id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "mata-kuliah/prodi/{$id_prodi}", $request->all());

            if ($response->successful()) {

                // Ambil ID dari response API
                $id = $response->json('data.id');

                return redirect()
                    ->route('mata-kuliah.detail', $id)
                    ->with('success', 'Data berhasil disimpan');
            }

            return back()->withErrors(
                $response->json('message') ?? 'Gagal menyimpan data'
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // Panggil endpoint update dari API
    public function update(Request $request, $id, $id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "mata-kuliah/{$id}/prodi/{$id_prodi}", $request->all());

            if ($response->successful()) {
                return back()->with('success', 'Data berhasil diperbarui');
            }

            return back()->withErrors(
                $response->json('message') ?? 'Gagal memperbarui data'
            );
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "mata-kuliah/{$id}");

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
     * Import matakuliah dari Excel
     */


    public function importExcel(Request $request, $id_prodi)
    {
        try {
            Log::info('=== START IMPORT EXCEL ===', [
                'id_prodi' => $id_prodi,
                'request' => $request->all()
            ]);

            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:10240', // max 10MB
            ]);

            $file = $request->file('file');

            Log::info('File diterima', [
                'nama' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $file->getPathname()
            ]);

            // Kirim ke API
            $response = Http::withToken($this->apiToken)
                ->attach(
                    'file',
                    fopen($file->getPathname(), 'r'),
                    $file->getClientOriginalName()
                )
                ->post($this->apiUrl . "mata-kuliah/import/prodi/{$id_prodi}");

            Log::info('Response dari API', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json()
            ]);

            if ($response->successful()) {
                Log::info('IMPORT BERHASIL');

                return response()->json([
                    'success' => true,
                    'message' => 'Data mata kuliah berhasil diimport',
                    'data' => $response->json()
                ]);
            }

            Log::warning('IMPORT GAGAL DARI API', [
                'status' => $response->status(),
                'errors' => $response->json()
            ]);

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal import data',
                'errors' => $response->json('errors') ?? []
            ], 422);
        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::error('VALIDATION ERROR', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            Log::error('EXCEPTION IMPORT EXCEL', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel untuk import matakuliah
     */
    public function downloadTemplate($id_prodi)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/format/prodi/{$id_prodi}");

            if ($response->successful()) {
                // Get the file content from API response
                $fileContent = $response->body();
                $filename = 'template_matakuliah_import.xlsx';

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
     * Export data matakuliah ke Excel
     */
    public function exportData(Request $request)
    {
        try {
            $id_prodi = $request->input('id_prodi');

            if (!$id_prodi) {
                return back()->with('error', 'ID Prodi wajib diisi');
            }

            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "mata-kuliah/export/prodi/{$id_prodi}");

            if ($response->successful()) {
                // Get the file content from API response
                $fileContent = $response->body();
                $filename = 'data_matakuliah_' . date('Y-m-d_H-i-s') . '.xlsx';

                return response($fileContent)
                    ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
                    ->header('Cache-Control', 'no-cache, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }

            return back()->with('error', 'Gagal export data');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
}
