<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NilaiTransferController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index(Request $request)
    {
        $selectedMahasiswaId = $request->query('mahasiswa_id');

        return view('akademik.nilai_transfer.index', [
            'selectedMahasiswaId' => $selectedMahasiswaId,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $query = array_filter([
                'id_mahasiswa' => $request->query('id_mahasiswa'),
                'q' => $request->query('q'),
                'page' => $request->query('page'),
                'per_page' => $request->query('per_page', 25),
            ], fn ($value) => filled($value));

            $response = $this->apiRequest('get', 'nilai-transfer', [], $query);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function summary(string $mahasiswaId): JsonResponse
    {
        try {
            $response = $this->apiRequest('get', "nilai-transfer/summary/{$mahasiswaId}");

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $payload = $request->only([
                'id_mahasiswa',
                'id_mata_kuliah',
                'kode_mata_kuliah_asal',
                'nama_mata_kuliah_asal',
                'sks_asal',
                'nilai_huruf_asal',
                'sks_diakui',
                'nilai_angka_diakui',
                'nilai_huruf_diakui',
                'nilai_indeks_diakui',
                'keterangan',
            ]);

            $response = $this->apiRequest('post', 'nilai-transfer', $payload);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $payload = $request->only([
                'id_mata_kuliah',
                'kode_mata_kuliah_asal',
                'nama_mata_kuliah_asal',
                'sks_asal',
                'nilai_huruf_asal',
                'sks_diakui',
                'nilai_angka_diakui',
                'nilai_huruf_diakui',
                'nilai_indeks_diakui',
                'keterangan',
            ]);

            $response = $this->apiRequest('put', "nilai-transfer/{$id}", $payload);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $response = $this->apiRequest('delete', "nilai-transfer/{$id}");

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mataKuliahOptions(Request $request): JsonResponse
    {
        try {
            $prodiId = $request->query('id_prodi');
            $endpoint = $prodiId ? "mata-kuliah/prodi/{$prodiId}" : 'mata-kuliah';
            $query = array_filter([
                'search' => ['value' => $request->query('q')],
                'length' => 500,
            ], fn ($value) => filled($value));

            $response = $this->apiRequest('get', $endpoint, [], $query);
            $json = $response->json();

            $rawList = $json['data'] ?? [];

            return response()->json([
                'success' => true,
                'data' => $rawList,
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mahasiswaOptions(Request $request): JsonResponse
    {
        try {
            $includeReguler = $request->boolean('include_reguler', false);

            $query = array_filter([
                'q' => $request->query('q'),
                'per_page' => 100,
            ], fn ($value) => filled($value));

            $response = $this->apiRequest('get', 'mahasiswa', [], $query);
            $json = $response->json();

            // Ekstrak array mahasiswa dari response backend (format: { success: true, data: { mahasiswa: [...], ... } })
            $rawList = $json['data']['mahasiswa'] ?? $json['data'] ?? [];

            // Filter mahasiswa jika tidak include_reguler (hanya RPL & Pindahan)
            if (! $includeReguler) {
                $rawList = array_values(array_filter($rawList, function ($m) {
                    return in_array($m['jenis_pendaftaran'] ?? '', ['RPL', 'Pindahan'], true)
                        || str_ends_with(strtoupper($m['nim'] ?? ''), 'B')
                        || strtoupper($m['jalur_masuk'] ?? '') === 'RPL';
                }));
            }

            // Prioritaskan mahasiswa RPL / Pindahan di urutan teratas
            usort($rawList, function ($a, $b) {
                $isRplA = in_array($a['jenis_pendaftaran'] ?? '', ['RPL', 'Pindahan'], true)
                    || str_ends_with(strtoupper($a['nim'] ?? ''), 'B')
                    || strtoupper($a['jalur_masuk'] ?? '') === 'RPL';
                $isRplB = in_array($b['jenis_pendaftaran'] ?? '', ['RPL', 'Pindahan'], true)
                    || str_ends_with(strtoupper($b['nim'] ?? ''), 'B')
                    || strtoupper($b['jalur_masuk'] ?? '') === 'RPL';

                if ($isRplA === $isRplB) {
                    return strcmp($a['nim'] ?? '', $b['nim'] ?? '');
                }

                return $isRplA ? -1 : 1;
            });

            return response()->json([
                'success' => true,
                'data' => $rawList,
                'is_filtered_rpl_only' => ! $includeReguler,
            ], $response->status());
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

        $url = rtrim($this->apiUrl, '/').'/'.ltrim($endpoint, '/');

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            'put' => $request->put($url, $payload),
            'delete' => $request->delete($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }
}
