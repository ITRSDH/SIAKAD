<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class StudentStudyAdministrationController extends Controller
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
        try {
            $filtersResponse = $this->apiRequest('get', 'administrasi-studi/filters');
            $summaryResponse = $this->apiRequest('get', 'administrasi-studi/summary');

            if (!$filtersResponse->successful()) {
                throw new \RuntimeException($filtersResponse->json('message') ?? 'Gagal mengambil filter administrasi studi.');
            }

            if (!$summaryResponse->successful()) {
                throw new \RuntimeException($summaryResponse->json('message') ?? 'Gagal mengambil ringkasan administrasi studi.');
            }

            return view('akademik.administrasi_studi.index', [
                'filters' => $filtersResponse->json('data', []),
                'workspaceSummary' => $summaryResponse->json('data', []),
                'activeTab' => request()->query('tab', 'konteks'),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function summary(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'administrasi-studi/summary', [], array_filter([
                'id_semester' => $request->query('id_semester'),
                'id_prodi' => $request->query('id_prodi'),
                'angkatan' => $request->query('angkatan'),
                'semester_ke' => $request->query('semester_ke'),
                'mode' => $request->query('mode'),
            ], fn($value) => filled($value)));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function batchHistory(): View|RedirectResponse
    {
        try {
            $response = $this->apiRequest('get', 'administrasi-studi/batches');

            if (!$response->successful()) {
                throw new \RuntimeException($response->json('message') ?? 'Gagal mengambil riwayat batch administrasi studi.');
            }

            return view('akademik.administrasi_studi.batches', [
                'batches' => $response->json('data', []),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('akademik.administrasi-studi.index', ['tab' => 'batch'])->with('error', $e->getMessage());
        }
    }

    public function showBatch(string $source, string $id): View|RedirectResponse
    {
        try {
            $response = $this->apiRequest('get', "administrasi-studi/batches/{$source}/{$id}");

            if (!$response->successful()) {
                throw new \RuntimeException($response->json('message') ?? 'Gagal mengambil detail batch administrasi studi.');
            }

            return view('akademik.administrasi_studi.show_batch', [
                'batch' => $response->json('data', []),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('akademik.administrasi-studi.batches')->with('error', $e->getMessage());
        }
    }

    public function eligibleHistoricalStudents(Request $request)
    {
        $validated = $request->validate([
            'id_semester' => 'required|string',
            'id_prodi' => 'nullable|string',
            'angkatan' => 'nullable|integer|min:1900|max:2100',
        ], [
            'id_semester.required' => 'Semester wajib dipilih.',
        ]);

        try {
            $response = $this->apiRequest('get', 'krs-historical/eligible-mahasiswa', [], array_filter($validated, fn($value) => filled($value)));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function historicalPackageClasses(Request $request)
    {
        $validated = $request->validate([
            'id_semester' => 'required|string',
            'id_prodi' => 'required|string',
            'semester_ke' => 'required|integer|min:1|max:14',
        ], [
            'id_semester.required' => 'Semester wajib dipilih.',
            'id_prodi.required' => 'Program studi wajib dipilih.',
            'semester_ke.required' => 'Semester ke wajib dipilih.',
        ]);

        try {
            $response = $this->apiRequest('get', 'krs-historical/package-classes', [], $validated);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function historicalRepeatCandidates(Request $request)
    {
        $validated = $request->validate([
            'id_semester' => 'required|string',
            'semester_ke' => 'required|integer|min:1|max:14',
            'id_mahasiswa' => 'required|array|min:1',
            'id_mahasiswa.*' => 'string',
        ], [
            'id_semester.required' => 'Semester wajib dipilih.',
            'semester_ke.required' => 'Semester ke wajib dipilih.',
            'id_mahasiswa.required' => 'Pilih minimal satu mahasiswa.',
        ]);

        try {
            $response = $this->apiRequest('get', 'krs-historical/repeat-candidates', [], $validated);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function previewHistorical(Request $request)
    {
        try {
            $action = (string) $request->input('action_type');
            $endpoint = $this->resolveHistoricalPreviewEndpoint($action);
            $payload = $this->prepareHistoricalPayload($request, $action, false);
            $response = $this->apiRequest('post', $endpoint, $payload);

            return response()->json($response->json(), $response->status());
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function executeHistorical(Request $request)
    {
        try {
            $action = (string) $request->input('action_type');
            $endpoint = $this->resolveHistoricalExecuteEndpoint($action);
            $payload = $this->prepareHistoricalPayload($request, $action, true);
            $response = $this->apiRequest('post', $endpoint, $payload);
            $body = $response->json();

            if ($response->successful()) {
                $batchId = $body['data']['batch_id'] ?? null;
                if (filled($batchId)) {
                    $body['data']['redirect_url'] = route('akademik.administrasi-studi.batches.show', [
                        'source' => 'historical',
                        'id' => $batchId,
                    ]);
                }
            }

            return response()->json($body, $response->status());
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function readyForKhs(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'administrasi-studi/ready-khs', [], array_filter([
                'id_semester' => $request->query('id_semester'),
                'id_prodi' => $request->query('id_prodi'),
                'angkatan' => $request->query('angkatan'),
            ], fn($value) => filled($value)));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function previewGenerateKhs(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa' => 'required|string',
            'id_semester' => 'required|string',
            'ipk' => 'nullable|numeric|min:0|max:4',
        ]);

        try {
            $response = $this->apiRequest('get', 'khs/preview/semester', [], $validated);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function executeGenerateKhs(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa' => 'required|string',
            'id_semester' => 'required|string',
            'is_final' => 'nullable|boolean',
            'ipk' => 'nullable|numeric|min:0|max:4',
        ]);

        try {
            $response = $this->apiRequest('post', 'khs/generate', $validated);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportImportTemplate(Request $request)
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
                return back()->withInput()->with('error', $response->json('message') ?? 'Gagal mengekspor template nilai.');
            }

            return $this->downloadBinaryResponse($response, 'template_nilai_khs.xlsx');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function uploadImport(Request $request): RedirectResponse
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
            $response = Http::withToken($this->apiToken)
                ->acceptJson()
                ->attach('file', fopen($file->getPathname(), 'r'), $file->getClientOriginalName())
                ->post($this->buildApiUrl('khs/import/upload'), [
                    'id_semester' => $validated['id_semester'],
                ]);

            if (!$response->successful()) {
                return redirect()
                    ->route('akademik.administrasi-studi.index', ['tab' => 'import'])
                    ->withInput()
                    ->with('error', $response->json('message') ?? 'Gagal mengunggah file import nilai.');
            }

            $batchId = $response->json('data.batch.id');

            if (!filled($batchId)) {
                return redirect()
                    ->route('akademik.administrasi-studi.index', ['tab' => 'import'])
                    ->withInput()
                    ->with('error', 'Batch import berhasil dibuat, tetapi ID batch tidak ditemukan.');
            }

            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('success', $response->json('message') ?? 'File import nilai berhasil diunggah.');
        } catch (\Exception $e) {
            return redirect()
                ->route('akademik.administrasi-studi.index', ['tab' => 'import'])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function previewImportBatch(string $batchId): View|RedirectResponse
    {
        try {
            $response = $this->apiRequest('get', "khs/import/{$batchId}/preview");

            if (!$response->successful()) {
                throw new \RuntimeException($response->json('message') ?? 'Gagal memuat preview import nilai.');
            }

            $payload = $response->json('data', []);

            return view('akademik.administrasi_studi.import_preview', [
                'batch' => $payload['batch'] ?? [],
                'metadata' => $payload['metadata'] ?? [],
                'subjects' => $payload['subjects'] ?? [],
                'preview' => $payload['preview'] ?? [],
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('akademik.administrasi-studi.batches.show', ['source' => 'import', 'id' => $batchId])
                ->with('error', $e->getMessage());
        }
    }

    public function processImportBatch(string $batchId): RedirectResponse
    {
        try {
            $response = $this->apiRequest('post', "khs/import/{$batchId}/process");

            if (!$response->successful()) {
                return redirect()
                    ->route('akademik.administrasi-studi.import.preview', $batchId)
                    ->with('error', $response->json('message') ?? 'Gagal memproses batch import nilai.');
            }

            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('success', $response->json('message') ?? 'Nilai import berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('error', $e->getMessage());
        }
    }

    public function finalizeImportBatch(Request $request, string $batchId): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $response = $this->apiRequest('post', "khs/import/{$batchId}/finalize", $validated);

            if (!$response->successful()) {
                return redirect()
                    ->route('akademik.administrasi-studi.import.preview', $batchId)
                    ->with('error', $response->json('message') ?? 'Gagal melakukan finalisasi batch import.');
            }

            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('success', $response->json('message') ?? 'Batch import berhasil difinalisasi.');
        } catch (\Exception $e) {
            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('error', $e->getMessage());
        }
    }

    public function rollbackImportBatch(string $batchId): RedirectResponse
    {
        try {
            $response = $this->apiRequest('post', "khs/import/{$batchId}/rollback");

            if (!$response->successful()) {
                return redirect()
                    ->route('akademik.administrasi-studi.import.preview', $batchId)
                    ->with('error', $response->json('message') ?? 'Gagal melakukan rollback batch import.');
            }

            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('success', $response->json('message') ?? 'Rollback batch import berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()
                ->route('akademik.administrasi-studi.import.preview', $batchId)
                ->with('error', $e->getMessage());
        }
    }

    public function exportImportErrors(string $batchId)
    {
        try {
            $response = $this->apiRequest('get', "khs/import/{$batchId}/export-errors");

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal mengekspor error import nilai.');
            }

            return $this->downloadBinaryResponse($response, "khs_import_errors_{$batchId}.xlsx");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function exportImportResults(string $batchId)
    {
        try {
            $response = $this->apiRequest('get', "khs/import/{$batchId}/export-results");

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal mengekspor hasil import nilai.');
            }

            return $this->downloadBinaryResponse($response, "khs_import_results_{$batchId}.xlsx");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function apiRequest(string $method, string $endpoint, array $payload = [], array $query = []): Response
    {
        $request = Http::withToken($this->requireApiToken())
            ->acceptJson();

        $url = $this->buildApiUrl($endpoint);

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function requireApiToken(): string
    {
        if (!filled($this->apiToken)) {
            throw new \RuntimeException('Sesi login Anda sudah habis. Silakan login ulang lalu coba lagi.');
        }

        return $this->apiToken;
    }

    private function buildApiUrl(string $endpoint): string
    {
        return rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
    }

    private function prepareHistoricalPayload(Request $request, string $action, bool $requireSelected): array
    {
        $payload = $request->validate([
            'id_semester' => 'required|string',
            'id_prodi' => 'nullable|string',
            'angkatan' => 'nullable|integer|min:1900|max:2100',
            'semester_ke' => 'nullable|integer|min:1|max:14',
            'build_mode' => 'nullable|string|in:krs_only,krs_with_scores',
            'selected_mahasiswa_ids' => 'nullable|array',
            'selected_mahasiswa_ids.*' => 'string',
            'notes' => 'nullable|string|max:2000',
            'students_payload' => 'nullable|array',
            'students_payload.*.id_mahasiswa' => 'required_with:students_payload|string',
            'students_payload.*.build_mode' => 'nullable|string|in:krs_only,krs_with_scores',
            'students_payload.*.courses' => 'nullable|array',
            'students_payload.*.courses.*.id_kelas_kuliah' => 'required_with:students_payload.*.courses|string',
            'students_payload.*.courses.*.nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'students_payload.*.courses.*.catatan' => 'nullable|string|max:1000',
            'students_payload.*.ipk' => 'nullable|numeric|min:0|max:4',
        ], [
            'id_semester.required' => 'Semester wajib dipilih.',
        ]);

        if ($requireSelected && empty($payload['selected_mahasiswa_ids'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'selected_mahasiswa_ids' => 'Pilih minimal satu mahasiswa untuk diproses.',
            ]);
        }

        if ($action === 'build_historical_krs') {
            $payload['build_mode'] = $payload['build_mode'] ?? 'krs_with_scores';

            if (empty($payload['id_prodi'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'id_prodi' => 'Program studi wajib dipilih untuk build KRS historis.',
                ]);
            }

            if (empty($payload['semester_ke'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'semester_ke' => 'Semester ke wajib dipilih untuk build KRS historis.',
                ]);
            }

            if ($requireSelected && empty($payload['students_payload'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'students_payload' => 'Form kelas dan nilai mahasiswa belum disiapkan.',
                ]);
            }
        } elseif ($action === 'generate_khs') {
            unset($payload['id_prodi'], $payload['angkatan'], $payload['semester_ke'], $payload['build_mode']);
        } else {
            unset($payload['id_prodi'], $payload['angkatan'], $payload['semester_ke'], $payload['students_payload'], $payload['build_mode']);
        }

        return array_filter($payload, function ($value) {
            if (is_array($value)) {
                return !empty($value);
            }

            return $value !== null && $value !== '';
        });
    }

    private function resolveHistoricalPreviewEndpoint(string $action): string
    {
        return match ($action) {
            'build_historical_krs' => 'krs-historical/preview/build',
            'reopen_historical_krs' => 'krs-historical/preview/reopen',
            'refinalize_historical_krs' => 'krs-historical/preview/refinalize',
            'reset_historical_krs' => 'krs-historical/preview/reset',
            'generate_khs' => 'krs-historical/preview/generate-khs',
            default => throw new \InvalidArgumentException('Aksi historical tidak dikenali.'),
        };
    }

    private function resolveHistoricalExecuteEndpoint(string $action): string
    {
        return match ($action) {
            'build_historical_krs' => 'krs-historical/execute/build',
            'reopen_historical_krs' => 'krs-historical/execute/reopen',
            'refinalize_historical_krs' => 'krs-historical/execute/refinalize',
            'reset_historical_krs' => 'krs-historical/execute/reset',
            'generate_khs' => 'krs-historical/execute/generate-khs',
            default => throw new \InvalidArgumentException('Aksi historical tidak dikenali.'),
        };
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
