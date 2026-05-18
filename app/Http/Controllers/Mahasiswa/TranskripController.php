<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TranskripController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        return view('mahasiswa.transkrip.index');
    }

    public function data()
    {
        return $this->handleApiRequest(function () {
            return $this->apiRequest('get', 'transkrip', [], $this->currentMahasiswaQuery());
        });
    }

    public function show(string $transkripId)
    {
        return $this->handleApiRequest(function () use ($transkripId) {
            return $this->apiRequest('get', "transkrip/{$transkripId}");
        });
    }

    private function handleApiRequest(callable $callback)
    {
        try {
            $response = $callback();

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
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function currentMahasiswaQuery(): array
    {
        $mahasiswaId = session('profile.id');

        if (!filled($mahasiswaId)) {
            throw new \RuntimeException('Profil mahasiswa tidak ditemukan. Silakan login ulang.');
        }

        return [
            'id_mahasiswa' => $mahasiswaId,
        ];
    }
}
