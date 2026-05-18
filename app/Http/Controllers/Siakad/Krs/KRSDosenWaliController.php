<?php

namespace App\Http\Controllers\Siakad\Krs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KRSDosenWaliController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        return view('dosen.dosenwali.krs_mahasiswa.index');
    }

    public function statistics()
    {
        try {
            $response = $this->apiRequest('get', 'krs-dosen/statistics');

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function pending()
    {
        try {
            $response = $this->apiRequest('get', 'krs-dosen/pending');

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
            $response = $this->apiRequest('get', "krs-dosen/{$id}");

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function approve(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'krs-dosen/approve', $request->only([
                'id_krs',
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

    public function revision(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'krs-dosen/revision', $request->only([
                'id_krs',
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

    public function reject(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'krs-dosen/reject', $request->only([
                'id_krs',
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
}
