<?php

namespace App\Http\Controllers\Siakad\AkhirStudi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TugasAkhirController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index()
    {
        try {
            $tugasAkhirResponse = $this->apiRequest('get', 'tugas-akhir');
            $mahasiswaResponse = $this->apiRequest('get', 'mahasiswa');
            $dosenResponse = $this->apiRequest('get', 'dosen');

            if (!$tugasAkhirResponse->successful()) {
                return back()->with('error', 'Gagal mengambil data tugas akhir dari API');
            }

            $tugasAkhir = $tugasAkhirResponse->json()['data'] ?? [];
            $mahasiswa = $mahasiswaResponse->successful() ? ($mahasiswaResponse->json()['data'] ?? []) : [];
            $dosen = $dosenResponse->successful() ? ($dosenResponse->json()['data'] ?? []) : [];

            return view('akhir_studi.tugas_akhir.index', compact('tugasAkhir', 'mahasiswa', 'dosen'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $response = $this->apiRequest('get', "tugas-akhir/{$id}");

            if (!$response->successful()) {
                return redirect()->route('tugas-akhir.index')
                    ->with('error', 'Gagal mengambil detail tugas akhir dari API.');
            }

            $tugasAkhir = $response->json()['data'] ?? [];

            return view('akhir_studi.tugas_akhir.show', compact('tugasAkhir'));
        } catch (\Exception $e) {
            return redirect()->route('tugas-akhir.index')->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->apiRequest('post', 'tugas-akhir', $request->only([
                'id_mahasiswa',
                'id_kurikulum',
                'jenis_tugas_akhir',
                'judul',
                'topik',
                'status',
                'tanggal_pengajuan',
                'tanggal_mulai_bimbingan',
                'tanggal_lulus',
                'is_active',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('tugas-akhir.index')->with('success', 'Data tugas akhir berhasil ditambahkan.');
            }

            return $this->redirectWithApiError($response, 'Gagal menambahkan data tugas akhir.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $response = $this->apiRequest('put', "tugas-akhir/{$id}", $request->only([
                'id_mahasiswa',
                'id_kurikulum',
                'jenis_tugas_akhir',
                'judul',
                'topik',
                'status',
                'tanggal_pengajuan',
                'tanggal_mulai_bimbingan',
                'tanggal_lulus',
                'is_active',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('tugas-akhir.index')->with('success', 'Data tugas akhir berhasil diperbarui.');
            }

            return $this->redirectWithApiError($response, 'Gagal memperbarui data tugas akhir.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function syncPembimbing(Request $request, string $id)
    {
        try {
            $response = $this->apiRequest('put', "tugas-akhir/{$id}/pembimbing", [
                'pembimbing' => $request->input('pembimbing', []),
            ]);

            if ($response->successful()) {
                return redirect()->route('tugas-akhir.index')->with('success', 'Pembimbing tugas akhir berhasil diperbarui.');
            }

            return $this->redirectWithApiError($response, 'Gagal memperbarui pembimbing tugas akhir.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function storeUjian(Request $request, string $id)
    {
        try {
            $response = $this->apiRequest('post', "tugas-akhir/{$id}/ujian", $request->only([
                'jenis_ujian',
                'tanggal_ujian',
                'nilai_ujian',
                'keputusan',
                'catatan',
            ]));

            if ($response->successful()) {
                return redirect()->route('tugas-akhir.index')->with('success', 'Ujian tugas akhir berhasil ditambahkan.');
            }

            return $this->redirectWithApiError($response, 'Gagal menambahkan ujian tugas akhir.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updateUjian(Request $request, string $id)
    {
        try {
            $response = $this->apiRequest('put', "tugas-akhir/ujian/{$id}", $request->only([
                'jenis_ujian',
                'tanggal_ujian',
                'nilai_ujian',
                'keputusan',
                'catatan',
            ]));

            if ($response->successful()) {
                return back()->with('success', 'Ujian tugas akhir berhasil diperbarui.');
            }

            return $this->redirectWithApiError($response, 'Gagal memperbarui ujian tugas akhir.');
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
