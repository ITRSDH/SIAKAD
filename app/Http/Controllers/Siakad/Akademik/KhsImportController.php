<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class KhsImportController extends Controller
{
    protected string $apiUrl;
    protected ?string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(): View|RedirectResponse
    {
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.nilai')
                ->with('info', 'Silakan lanjutkan import nilai melalui Administrasi Studi Mahasiswa.');
        }

        try {
            [$prodi, $semesterOptions, $history] = $this->buildSharedData();

            return view('akademik.khs.import.index', [
                'prodiOptions' => $prodi,
                'semesterOptions' => $semesterOptions,
                'historyItems' => collect($history)->take(5)->values()->all(),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function history(): View|RedirectResponse
    {
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.batches')
                ->with('info', 'Riwayat import nilai tersedia di Administrasi Studi Mahasiswa.');
        }

        try {
            $historyResponse = $this->apiRequest('get', 'khs/import/history');
            if (!$historyResponse->successful()) {
                throw new \RuntimeException($historyResponse->json('message') ?? 'Gagal mengambil riwayat import KHS.');
            }

            return view('akademik.khs.import.history', [
                'historyItems' => $this->normalizeList($historyResponse->json('data', [])),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $batchId): View|RedirectResponse
    {
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.batches.show', ['source' => 'import', 'id' => $batchId])
                ->with('info', 'Detail batch import tersedia di Administrasi Studi Mahasiswa.');
        }

        try {
            $detailResponse = $this->apiRequest('get', "khs/import/{$batchId}");
            if (!$detailResponse->successful()) {
                throw new \RuntimeException($detailResponse->json('message') ?? 'Gagal mengambil detail batch import KHS.');
            }

            return view('akademik.khs.import.show', [
                'batch' => $detailResponse->json('data', []),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('akademik.khs.import.history')->with('error', $e->getMessage());
        }
    }

    public function preview(string $batchId): View|RedirectResponse
    {
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.nilai')
                ->with('info', 'Preview import nilai tersedia di Administrasi Studi Mahasiswa.');
        }

        try {
            $previewResponse = $this->apiRequest('get', "khs/import/{$batchId}/preview");
            if (!$previewResponse->successful()) {
                throw new \RuntimeException($previewResponse->json('message') ?? 'Gagal memuat preview import KHS.');
            }

            $payload = $previewResponse->json('data', []);
            $batch = $payload['batch'] ?? [];
            $preview = $payload['preview'] ?? [];
            $processedKhsIds = collect($batch['summary']['processed_khs_ids'] ?? [])->filter()->values()->all();

            return view('akademik.khs.import.preview', [
                'batch' => $batch,
                'metadata' => $payload['metadata'] ?? [],
                'subjects' => $payload['subjects'] ?? [],
                'preview' => $preview,
                'processedKhsIds' => $processedKhsIds,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('akademik.khs.import.history')->with('error', $e->getMessage());
        }
    }

    public function upload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_semester' => 'required|string',
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'id_semester.required' => 'Semester wajib dipilih.',
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes' => 'File harus berformat .xlsx atau .xls.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $file = $request->file('file');
            $response = Http::withToken($this->requireApiToken())
                ->acceptJson()
                ->attach('file', fopen($file->getPathname(), 'r'), $file->getClientOriginalName())
                ->post($this->buildApiUrl('khs/import/upload'), [
                    'id_semester' => $validated['id_semester'],
                ]);

            if (!$response->successful()) {
                return back()->withInput()->with('error', $response->json('message') ?? 'Gagal mengunggah file import KHS.');
            }

            $batchId = $response->json('data.batch.id');
            if (!filled($batchId)) {
                return back()->withInput()->with('error', 'Batch import berhasil dibuat, tetapi ID batch tidak ditemukan.');
            }

            return redirect()
                ->route('akademik.khs.import.preview', $batchId)
                ->with('success', $response->json('message') ?? 'File import KHS berhasil diunggah.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function process(string $batchId): RedirectResponse
    {
        try {
            $response = $this->apiRequest('post', "khs/import/{$batchId}/process");

            if (!$response->successful()) {
                return redirect()
                    ->route('akademik.khs.import.preview', $batchId)
                    ->with('error', $response->json('message') ?? 'Gagal memproses batch import KHS.');
            }

            return redirect()
                ->route('akademik.khs.import.history')
                ->with('success', $response->json('message') ?? 'Nilai import berhasil disimpan ke KRS dan KHS berhasil dibentuk.');
        } catch (\Exception $e) {
            return redirect()->route('akademik.khs.import.preview', $batchId)->with('error', $e->getMessage());
        }
    }

    public function rollback(string $batchId): RedirectResponse
    {
        try {
            $response = $this->apiRequest('post', "khs/import/{$batchId}/rollback");

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal melakukan rollback batch import KHS.');
            }

            return back()->with('success', $response->json('message') ?? 'Rollback batch import KHS berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function finalizeBatch(Request $request, string $batchId): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $response = $this->apiRequest('post', "khs/import/{$batchId}/finalize", $validated);

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal melakukan finalisasi semua KHS dalam batch ini.');
            }

            return back()->with('success', $response->json('message') ?? 'Semua KHS dalam batch ini berhasil difinalisasi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function exportTemplate(Request $request)
    {
        $validated = $request->validate([
            'angkatan' => 'required|integer',
            'id_prodi' => 'required|string',
            'id_semester' => 'required|string',
            'semester_ke' => 'required|integer|min:1',
        ], [
            'angkatan.required' => 'Angkatan wajib diisi.',
            'id_prodi.required' => 'Program studi wajib dipilih.',
            'id_semester.required' => 'Semester akademik wajib dipilih.',
            'semester_ke.required' => 'Semester ke wajib diisi.',
        ]);

        try {
            $response = $this->apiRequest('get', 'khs/import/template/export', [], $validated);

            if (!$response->successful()) {
                return back()->withInput()->with('error', $response->json('message') ?? 'Gagal mengekspor template nilai KHS.');
            }

            return $this->downloadBinaryResponse($response, 'template_nilai_khs.xlsx');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function exportErrors(string $batchId)
    {
        try {
            $response = $this->apiRequest('get', "khs/import/{$batchId}/export-errors");

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal mengekspor error report batch KHS.');
            }

            return $this->downloadBinaryResponse($response, "khs_import_errors_{$batchId}.xlsx");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function exportResults(string $batchId)
    {
        try {
            $response = $this->apiRequest('get', "khs/import/{$batchId}/export-results");

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal mengekspor hasil import KHS.');
            }

            return $this->downloadBinaryResponse($response, "khs_import_results_{$batchId}.xlsx");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function buildSharedData(): array
    {
        $prodiResponse = $this->apiRequest('get', 'dropdown', [], ['type' => 'prodi']);
        $tahunAkademikResponse = $this->apiRequest('get', 'tahun-akademik');
        $historyResponse = $this->apiRequest('get', 'khs/import/history');

        if (!$prodiResponse->successful()) {
            throw new \RuntimeException($prodiResponse->json('message') ?? 'Gagal mengambil daftar program studi.');
        }

        if (!$tahunAkademikResponse->successful()) {
            throw new \RuntimeException($tahunAkademikResponse->json('message') ?? 'Gagal mengambil daftar tahun akademik.');
        }

        if (!$historyResponse->successful()) {
            throw new \RuntimeException($historyResponse->json('message') ?? 'Gagal mengambil riwayat import KHS.');
        }

        return [
            $this->normalizeProdiOptions($prodiResponse->json('data', [])),
            $this->extractSemesterOptions($tahunAkademikResponse->json('data', [])),
            $this->normalizeList($historyResponse->json('data', [])),
        ];
    }

    private function normalizeProdiOptions(array $payload): array
    {
        $items = $this->normalizeList($payload, ['prodi']);

        return collect($items)
            ->map(function (array $item) {
                return [
                    'id' => $item['id'] ?? null,
                    'nama_prodi' => $item['nama_prodi']
                        ?? $item['prodi']
                        ?? trim((string) (($item['jenjang_pendidikan'] ?? '') . ' ' . ($item['nama'] ?? 'Program Studi'))),
                ];
            })
            ->filter(fn(array $item) => filled($item['id']))
            ->values()
            ->all();
    }

    private function extractSemesterOptions(array $tahunAkademikItems): array
    {
        return collect($tahunAkademikItems)
            ->flatMap(function (array $tahun) {
                $tahunLabel = $tahun['tahun_akademik'] ?? $tahun['nama'] ?? '-';

                return collect($tahun['semester'] ?? [])->map(function (array $semester) use ($tahunLabel) {
                    return [
                        'id' => $semester['id'] ?? null,
                        'label' => trim(($semester['nama_semester'] ?? $semester['nama'] ?? 'Semester') . ' ' . $tahunLabel),
                        'is_active' => strtolower((string) ($semester['status'] ?? '')) === 'aktif' || ($semester['is_active'] ?? false),
                    ];
                });
            })
            ->filter(fn(array $item) => filled($item['id']))
            ->values()
            ->all();
    }

    private function normalizeList(array $payload, array $preferredKeys = []): array
    {
        foreach ($preferredKeys as $key) {
            $candidate = $payload[$key] ?? null;

            if (is_array($candidate) && array_is_list($candidate)) {
                return $candidate;
            }
        }

        return array_is_list($payload) ? $payload : [];
    }

    private function apiRequest(string $method, string $endpoint, array $payload = [], array $query = []): Response
    {
        $request = Http::withToken($this->requireApiToken())
            ->acceptJson();

        $url = $this->buildApiUrl($endpoint);

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            'put' => $request->put($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function requireApiToken(): string
    {
        if (!filled($this->apiToken)) {
            throw new \RuntimeException('Sesi login Anda sudah habis. Silakan login ulang lalu coba import nilai lagi.');
        }

        return $this->apiToken;
    }

    private function buildApiUrl(string $endpoint): string
    {
        return rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
    }

    private function downloadBinaryResponse(Response $response, string $fallbackFilename)
    {
        $contentType = $response->header('Content-Type', 'application/octet-stream');
        $disposition = $response->header('Content-Disposition');
        $filename = $fallbackFilename;

        if (preg_match('/filename=\"?([^\";]+)\"?/i', (string) $disposition, $matches)) {
            $filename = $matches[1];
        }

        return response($response->body())
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
