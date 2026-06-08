<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PeriodeKRSController extends Controller
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
            // Ambil data periode KRS dari API (tanpa paginate)
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'periode-krs');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data periode KRS dari API');
            }

            $periodeKRS = $response->json()['data'] ?? [];

            return view('masterdata.periode_krs.index', compact('periodeKRS'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create(DropdownService $dropdownService)
    {
        $semester = $dropdownService->get('semester');
        return view('masterdata.periode_krs.create', [
            'semester' => $semester['semester'] ?? [],
        ]);
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'periode-krs', $request->all());

            if ($response->successful()) {
                return redirect()->route('periode-krs.index')->with('success', 'Data periode KRS berhasil ditambahkan');
            }

            // Tangani error dari API dengan format yang konsisten
            $apiResponse = $response->json();
            $errorMessage = 'Gagal menyimpan data ke API';
            $errorDetails = [];

            // Ambil pesan error utama
            if (isset($apiResponse['message'])) {
                $errorMessage = $apiResponse['message'];
            }

            // Ambil error field validation
            if (isset($apiResponse['errors']) && is_array($apiResponse['errors'])) {
                $errorDetails = $apiResponse['errors'];
            }

            // Ambil error detail tambahan (jika ada)
            if (isset($apiResponse['error'])) {
                $errorMessage = $apiResponse['error'];
            }

            return back()
                ->with('error', $errorMessage)
                ->withErrors($errorDetails)
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "periode-krs/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function edit($id, DropdownService $dropdownService)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "periode-krs/{$id}");

            if ($response->successful()) {
                $periodeKRS = $response->json()['data'] ?? [];

                $semester = $dropdownService->get('semester');
                return view('masterdata.periode_krs.edit', [
                    'periodeKRS' => $periodeKRS,
                    'semester' => $semester['semester'] ?? [],
                ]);
            }

            return back()->with('error', 'Gagal mengambil data dari API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "periode-krs/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('periode-krs.index')->with('success', 'Data periode KRS berhasil diperbarui');
            }

            // Tangani error dari API dengan format yang konsisten
            $apiResponse = $response->json();
            $errorMessage = 'Gagal memperbarui data di API';
            $errorDetails = [];

            // Ambil pesan error utama
            if (isset($apiResponse['message'])) {
                $errorMessage = $apiResponse['message'];
            }

            // Ambil error field validation
            if (isset($apiResponse['errors']) && is_array($apiResponse['errors'])) {
                $errorDetails = $apiResponse['errors'];
            }

            // Ambil error detail tambahan (jika ada)
            if (isset($apiResponse['error'])) {
                $errorMessage = $apiResponse['error'];
            }

            return back()
                ->with('error', $errorMessage)
                ->withErrors($errorDetails)
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "periode-krs/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data di API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
