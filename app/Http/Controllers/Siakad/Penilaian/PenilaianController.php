<?php

namespace App\Http\Controllers\Siakad\Penilaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PenilaianController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        return view('dosen.penilaian.index');
    }

    public function kelasKuliah(Request $request)
    {
        return $this->handleApiRequest(function () use ($request) {
            return $this->apiRequest('get', 'kelas-kuliah/dosen-saya', [], array_filter([
                'search' => $request->query('search'),
                'page' => $request->query('page'),
                'per_page' => $request->query('per_page'),
            ], fn ($value) => filled($value)));
        });
    }

    public function komponenByKelas(string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($kelasKuliahId) {
            return $this->apiRequest('get', "penilaian/kelas/{$kelasKuliahId}/komponen");
        });
    }

    public function storeKomponen(Request $request, string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($request, $kelasKuliahId) {
            return $this->apiRequest('post', "penilaian/kelas/{$kelasKuliahId}/komponen", [
                'nama' => $request->input('nama'),
                'bobot' => $request->input('bobot'),
                'urutan' => $request->input('urutan'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });
    }

    public function updateKomponen(Request $request, string $id)
    {
        return $this->handleApiRequest(function () use ($request, $id) {
            return $this->apiRequest('put', "penilaian/komponen/{$id}", [
                'nama' => $request->input('nama'),
                'bobot' => $request->input('bobot'),
                'urutan' => $request->input('urutan'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });
    }

    public function destroyKomponen(string $id)
    {
        return $this->handleApiRequest(function () use ($id) {
            return $this->apiRequest('delete', "penilaian/komponen/{$id}");
        });
    }

    public function nilaiByKelas(string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($kelasKuliahId) {
            return $this->apiRequest('get', "penilaian/kelas/{$kelasKuliahId}/nilai");
        });
    }

    public function updateNilaiKomponen(Request $request, string $komponenId)
    {
        return $this->handleApiRequest(function () use ($request, $komponenId) {
            return $this->apiRequest('put', "penilaian/komponen/{$komponenId}/nilai", [
                'nilai' => $request->input('nilai', []),
            ]);
        });
    }

    public function publishFinal(string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($kelasKuliahId) {
            return $this->apiRequest('post', "penilaian/kelas/{$kelasKuliahId}/publish-final");
        });
    }

    public function reopen(Request $request, string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($request, $kelasKuliahId) {
            return $this->apiRequest('post', "penilaian/kelas/{$kelasKuliahId}/reopen", $request->only([
                'reopen_reason',
            ]));
        });
    }

    public function manualFinal(Request $request, string $krsDetailId)
    {
        return $this->handleApiRequest(function () use ($request, $krsDetailId) {
            return $this->apiRequest('put', "penilaian/krs-detail/{$krsDetailId}/manual-final", $request->only([
                'nilai_akhir',
                'nilai_huruf',
                'catatan',
            ]));
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
            'put' => $request->put($url, $payload),
            'delete' => $request->delete($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }
}
