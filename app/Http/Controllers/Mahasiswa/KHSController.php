<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class KHSController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        return view('mahasiswa.khs.index');
    }

    public function data()
    {
        return $this->handleApiRequest(function () {
            return $this->apiRequest('get', 'khs', [], $this->currentMahasiswaQuery());
        });
    }

    public function show(string $khsId)
    {
        return $this->handleApiRequest(function () use ($khsId) {
            return $this->apiRequest('get', "khs/{$khsId}");
        });
    }

    public function print(string $khsId)
    {
        return $this->renderPrintView($khsId, true);
    }

    public function download(string $khsId)
    {
        return $this->renderPrintView($khsId, true, true);
    }

    private function renderPrintView(string $khsId, bool $autoPrint = true, bool $downloadMode = false)
    {
        try {
            $response = $this->apiRequest('get', "khs/{$khsId}");

            if (!$response->successful()) {
                return $this->redirectBackWithError($response->json('message') ?? 'Gagal memuat data KHS untuk dicetak.');
            }

            $payload = $response->json();
            $khs = $payload['data'] ?? null;

            if (!(($payload['success'] ?? false)) || !$khs) {
                return $this->redirectBackWithError($payload['message'] ?? 'Data KHS tidak ditemukan.');
            }

            $mahasiswa = $khs['mahasiswa'] ?? [];
            $mahasiswaDetail = [];

            if (!empty($mahasiswa['id'])) {
                $mahasiswaResponse = $this->apiRequest('get', "mahasiswa/{$mahasiswa['id']}");
                if ($mahasiswaResponse->successful()) {
                    $mahasiswaDetail = $mahasiswaResponse->json('data') ?? [];
                }
            }

            return view('mahasiswa.khs.print', [
                'khs' => $khs,
                'mahasiswaDetail' => $mahasiswaDetail,
                'autoPrint' => $autoPrint,
                'downloadMode' => $downloadMode,
            ]);
        } catch (\Exception $e) {
            return $this->redirectBackWithError($e->getMessage());
        }
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

    private function redirectBackWithError(string $message): RedirectResponse
    {
        return redirect()->route('student.khs.index')->with('error', $message);
    }
}
