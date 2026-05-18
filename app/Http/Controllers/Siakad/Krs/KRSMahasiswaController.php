<?php

namespace App\Http\Controllers\Siakad\Krs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;

class KRSMahasiswaController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        return view('mahasiswa.krs.index');
    }

    public function current()
    {
        try {
            $response = $this->apiRequest('get', 'krs-mahasiswa/current');

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'krs-mahasiswa', $request->only([
                'id_semester',
                'catatan',
            ]));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function initCurrent()
    {
        try {
            $response = $this->apiRequest('post', 'krs-mahasiswa/current/init');

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function penawaranMK(Request $request)
    {
        try {
            $query = array_filter([
                'id_krs' => $request->query('id_krs'),
                'id_semester' => $request->query('id_semester'),
            ], fn($value) => filled($value));

            $response = $this->apiRequest('get', 'krs-mahasiswa/available-mata-kuliah', [], $query);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function repeatCandidates(Request $request)
    {
        try {
            $query = array_filter([
                'id_krs' => $request->query('id_krs'),
            ], fn($value) => filled($value));

            $response = $this->apiRequest('get', 'krs-mahasiswa/repeat-candidates', [], $query);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function dataKrs()
    {
        try {
            $response = $this->apiRequest('get', 'krs-mahasiswa');

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function statistics()
    {
        try {
            $response = $this->apiRequest('get', 'krs-mahasiswa/statistics');

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $response = $this->apiRequest('get', "krs-mahasiswa/{$id}");

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function print(string $id)
    {
        try {
            $response = $this->apiRequest('get', "krs-mahasiswa/{$id}");

            if (!$response->successful()) {
                return $this->redirectBackWithError($response->json('message') ?? 'Gagal memuat data KRS untuk dicetak.');
            }

            $payload = $response->json();
            $krs = $payload['data'] ?? null;

            if (!(($payload['success'] ?? false)) || !$krs) {
                return $this->redirectBackWithError($payload['message'] ?? 'Data KRS tidak ditemukan.');
            }

            if (($krs['status_approval'] ?? null) !== 'approved') {
                return $this->redirectBackWithError('KRS hanya dapat dicetak setelah disetujui dosen wali.');
            }

            return view('mahasiswa.krs.print', [
                'krs' => $krs,
            ]);
        } catch (\Exception $e) {
            return $this->redirectBackWithError($e->getMessage());
        }
    }

    public function validationSummary(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'krs-mahasiswa/validation-summary', [], [
                'id_krs' => $request->query('id_krs'),
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addMataKuliah(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'krs-mahasiswa/add-mata-kuliah', $request->only([
                'id_krs',
                'id_kelas_kuliah',
                'force_override',
                'override_note',
            ]));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function submit(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'krs-mahasiswa/submit', $request->only([
                'id_krs',
            ]));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeMataKuliah(string $krsId, string $kelasKuliahId)
    {
        try {
            $response = $this->apiRequest('delete', "krs-mahasiswa/{$krsId}/remove-mata-kuliah/{$kelasKuliahId}");

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function apiRequest(string $method, string $endpoint, array $payload = [], array $query = []): Response
    {
        $request = Http::withToken(session('access_token'))
            ->acceptJson();

        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            'delete' => $request->delete($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function redirectBackWithError(string $message): RedirectResponse
    {
        return redirect()->route('krs.index')->with('error', $message);
    }
}
