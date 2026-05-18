<?php

namespace App\Http\Controllers\Siakad\AkhirStudi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KelulusanController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        try {
            $kelulusanResponse = $this->apiRequest('get', 'kelulusan');
            $mahasiswaResponse = $this->apiRequest('get', 'mahasiswa');

            if (!$kelulusanResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data kelulusan dari API');
            }

            $kelulusan = $kelulusanResponse->json()['data'] ?? [];
            $mahasiswa = $mahasiswaResponse->successful() ? ($mahasiswaResponse->json()['data'] ?? []) : [];

            return view('akhir_studi.kelulusan.index', compact('kelulusan', 'mahasiswa'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $response = $this->apiRequest('get', "kelulusan/{$id}");

            if (!$response->successful()) {
                return redirect()->route('kelulusan.index')
                    ->with('error', 'Gagal mengambil detail kelulusan dari API.');
            }

            $kelulusan = $response->json()['data'] ?? [];

            return view('akhir_studi.kelulusan.show', compact('kelulusan'));
        } catch (\Exception $e) {
            return redirect()->route('kelulusan.index')->with('error', $e->getMessage());
        }
    }

    public function generate(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'kelulusan/generate', $request->only([
                'id_mahasiswa',
                'tanggal_lulus',
                'nomor_sk',
                'nomor_ijazah',
                'status',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('kelulusan.index')->with('success', 'Kelulusan berhasil digenerate.');
            }

            return $this->redirectWithApiError($response, 'Gagal generate kelulusan.');
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
