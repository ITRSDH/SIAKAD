<?php

namespace App\Http\Controllers\Siakad\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PertemuanPresensiController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        return view('dosen.pertemuan_presensi.index');
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

    public function pertemuanByKelas(string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($kelasKuliahId) {
            return $this->apiRequest('get', "pertemuan-kuliah/kelas/{$kelasKuliahId}");
        });
    }

    public function storePertemuan(Request $request, string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($request, $kelasKuliahId) {
            return $this->apiRequest('post', "pertemuan-kuliah/kelas/{$kelasKuliahId}", $request->only([
                'judul_pertemuan',
                'pertemuan_ke',
                'tanggal_pertemuan',
                'materi',
                'catatan',
                'status',
            ]));
        });
    }

    public function updatePertemuan(Request $request, string $id)
    {
        return $this->handleApiRequest(function () use ($request, $id) {
            return $this->apiRequest('put', "pertemuan-kuliah/{$id}", $request->only([
                'judul_pertemuan',
                'pertemuan_ke',
                'tanggal_pertemuan',
                'materi',
                'catatan',
                'status',
            ]));
        });
    }

    public function presensiByPertemuan(string $pertemuanId)
    {
        return $this->handleApiRequest(function () use ($pertemuanId) {
            return $this->apiRequest('get', "presensi-kuliah/pertemuan/{$pertemuanId}");
        });
    }

    public function generatePeserta(string $pertemuanId)
    {
        return $this->handleApiRequest(function () use ($pertemuanId) {
            return $this->apiRequest('post', "presensi-kuliah/pertemuan/{$pertemuanId}/generate-peserta");
        });
    }

    public function updatePresensi(Request $request, string $pertemuanId)
    {
        return $this->handleApiRequest(function () use ($request, $pertemuanId) {
            return $this->apiRequest('put', "presensi-kuliah/pertemuan/{$pertemuanId}", [
                'presensi' => $request->input('presensi', []),
            ]);
        });
    }

    public function rekapByKelas(string $kelasKuliahId)
    {
        return $this->handleApiRequest(function () use ($kelasKuliahId) {
            return $this->apiRequest('get', "presensi-kuliah/kelas/{$kelasKuliahId}/rekap");
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
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }
}
