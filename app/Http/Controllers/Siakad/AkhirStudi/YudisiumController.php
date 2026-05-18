<?php

namespace App\Http\Controllers\Siakad\AkhirStudi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YudisiumController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        try {
            $yudisiumResponse = $this->apiRequest('get', 'yudisium');
            $mahasiswaResponse = $this->apiRequest('get', 'mahasiswa');

            if (!$yudisiumResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data yudisium dari API');
            }

            $yudisium = $yudisiumResponse->json()['data'] ?? [];
            $mahasiswa = $mahasiswaResponse->successful() ? ($mahasiswaResponse->json()['data'] ?? []) : [];

            return view('akhir_studi.yudisium.index', compact('yudisium', 'mahasiswa'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $response = $this->apiRequest('get', "yudisium/{$id}");

            if (!$response->successful()) {
                return redirect()->route('yudisium.index')
                    ->with('error', 'Gagal mengambil detail yudisium dari API.');
            }

            $yudisium = $response->json()['data'] ?? [];

            return view('akhir_studi.yudisium.show', compact('yudisium'));
        } catch (\Exception $e) {
            return redirect()->route('yudisium.index')->with('error', $e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'yudisium/preview/mahasiswa', [], array_filter([
                'id_mahasiswa' => $request->query('id_mahasiswa'),
                'id_kurikulum' => $request->query('id_kurikulum'),
            ], fn ($value) => filled($value)));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generate(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'yudisium/generate', $request->only([
                'id_mahasiswa',
                'id_kurikulum',
                'tanggal_yudisium',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('yudisium.index')->with('success', 'Yudisium berhasil digenerate.');
            }

            return $this->redirectWithApiError($response, 'Gagal generate yudisium.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function apiRequest(string $method, string $endpoint, array $payload = [], array $query = []): Response
    {
        $request = Http::withToken(session('access_token'))->acceptJson();
        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function redirectWithApiError(Response $response, string $fallbackMessage)
    {
        $body = $response->json();
        $message = $body['message'] ?? $fallbackMessage;
        $errors = isset($body['errors']) && is_array($body['errors']) ? $body['errors'] : [];

        return back()->with('error', $message)->withErrors($errors)->withInput();
    }
}
