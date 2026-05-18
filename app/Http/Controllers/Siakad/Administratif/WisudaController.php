<?php

namespace App\Http\Controllers\Siakad\Administratif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WisudaController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function indexPeriode()
    {
        try {
            $response = $this->apiRequest('get', 'wisuda/periode');

            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data periode wisuda dari API');
            }

            $periodeWisuda = $response->json()['data'] ?? [];

            return view('administratif.wisuda.index', compact('periodeWisuda'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showPeriode(string $id)
    {
        try {
            $response = $this->apiRequest('get', "wisuda/periode/{$id}");

            if (!$response->successful()) {
                return redirect()->route('wisuda.periode.index')
                    ->with('error', 'Gagal mengambil detail periode wisuda dari API.');
            }

            $periode = $response->json()['data'] ?? [];
            $pesertaWisuda = $periode['peserta'] ?? [];

            return view('administratif.wisuda.show', compact('periode', 'pesertaWisuda'));
        } catch (\Exception $e) {
            return redirect()->route('wisuda.periode.index')->with('error', $e->getMessage());
        }
    }

    public function storePeriode(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'wisuda/periode', $request->only([
                'nama_periode',
                'tanggal_mulai_pendaftaran',
                'tanggal_selesai_pendaftaran',
                'tanggal_wisuda',
                'lokasi',
                'status',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('wisuda.periode.index')
                    ->with('success', 'Periode wisuda berhasil ditambahkan.');
            }

            return $this->redirectWithApiError($response, 'Gagal menambahkan periode wisuda.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updatePeriode(Request $request, string $id)
    {
        try {
            $response = $this->apiRequest('put', "wisuda/periode/{$id}", $request->only([
                'nama_periode',
                'tanggal_mulai_pendaftaran',
                'tanggal_selesai_pendaftaran',
                'tanggal_wisuda',
                'lokasi',
                'status',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('wisuda.periode.index')
                    ->with('success', 'Periode wisuda berhasil diperbarui.');
            }

            return $this->redirectWithApiError($response, 'Gagal memperbarui periode wisuda.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function peserta(string $periodeId)
    {
        try {
            $periodeResponse = $this->apiRequest('get', "wisuda/periode/{$periodeId}");
            $pesertaResponse = $this->apiRequest('get', "wisuda/periode/{$periodeId}/peserta");
            $kelulusanResponse = $this->apiRequest('get', 'kelulusan');

            if (!$periodeResponse->successful() || !$pesertaResponse->successful()) {
                return redirect()->route('wisuda.periode.index')
                    ->with('error', 'Gagal mengambil data periode atau peserta wisuda.');
            }

            $periode = $periodeResponse->json()['data'] ?? [];
            $pesertaWisuda = $pesertaResponse->json()['data'] ?? [];
            $kelulusan = $kelulusanResponse->successful() ? ($kelulusanResponse->json()['data'] ?? []) : [];

            return view('administratif.wisuda.peserta', compact('periode', 'pesertaWisuda', 'kelulusan'));
        } catch (\Exception $e) {
            return redirect()->route('wisuda.periode.index')->with('error', $e->getMessage());
        }
    }

    public function showPeserta(string $id)
    {
        try {
            $response = $this->apiRequest('get', "wisuda/peserta/{$id}");

            if (!$response->successful()) {
                return redirect()->route('wisuda.periode.index')
                    ->with('error', 'Gagal mengambil detail peserta wisuda dari API.');
            }

            $peserta = $response->json()['data'] ?? [];
            $periode = $peserta['periode_wisuda'] ?? [];

            return view('administratif.wisuda.show-peserta', compact('peserta', 'periode'));
        } catch (\Exception $e) {
            return redirect()->route('wisuda.periode.index')->with('error', $e->getMessage());
        }
    }

    public function storePeserta(Request $request, string $periodeId)
    {
        try {
            $response = $this->apiRequest('post', "wisuda/periode/{$periodeId}/peserta", $request->only([
                'id_mahasiswa',
                'tanggal_daftar',
                'status',
                'status_validasi_administrasi',
                'nomor_peserta',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('wisuda.peserta.index', $periodeId)
                    ->with('success', 'Peserta wisuda berhasil ditambahkan.');
            }

            return $this->redirectWithApiError($response, 'Gagal menambahkan peserta wisuda.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updatePeserta(Request $request, string $id)
    {
        try {
            $response = $this->apiRequest('put', "wisuda/peserta/{$id}", $request->only([
                'tanggal_daftar',
                'status',
                'status_validasi_administrasi',
                'nomor_peserta',
                'catatan',
            ]));

            if ($response->successful()) {
                return back()->with('success', 'Peserta wisuda berhasil diperbarui.');
            }

            return $this->redirectWithApiError($response, 'Gagal memperbarui peserta wisuda.');
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
            'put' => $request->put($url, $payload),
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
