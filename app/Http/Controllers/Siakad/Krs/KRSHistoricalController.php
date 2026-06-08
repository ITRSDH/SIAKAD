<?php

namespace App\Http\Controllers\Siakad\Krs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class KRSHistoricalController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(): View|RedirectResponse
    {
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.index', ['tab' => 'krs'])
                ->with('info', 'Silakan lanjutkan pengelolaan riwayat studi melalui Administrasi Studi Mahasiswa.');
        }

        try {
            $filtersResponse = $this->apiRequest('get', 'krs-historical/filters');
            $batchesResponse = $this->apiRequest('get', 'krs-historical/batches', [], ['per_page' => 5]);

            if (!$filtersResponse->successful()) {
                throw new \RuntimeException($filtersResponse->json('message') ?? 'Gagal mengambil filter riwayat studi historis.');
            }

            if (!$batchesResponse->successful()) {
                throw new \RuntimeException($batchesResponse->json('message') ?? 'Gagal mengambil histori batch riwayat studi historis.');
            }

            return view('akademik.riwayat_studi.index', [
                'filters' => $filtersResponse->json('data', []),
                'recentBatches' => $batchesResponse->json('data', []),
                'recentBatchMeta' => $batchesResponse->json('meta', []),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function eligibleMahasiswa(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'krs-historical/eligible-mahasiswa', [], array_filter([
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

    public function preview(Request $request)
    {
        try {
            $action = (string) $request->input('action_type');
            $endpoint = $this->resolvePreviewEndpoint($action);
            $payload = $this->preparePayload($request, $action, false);

            $response = $this->apiRequest('post', $endpoint, $payload);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function execute(Request $request)
    {
        try {
            $action = (string) $request->input('action_type');
            $endpoint = $this->resolveExecuteEndpoint($action);
            $payload = $this->preparePayload($request, $action, true);

            $response = $this->apiRequest('post', $endpoint, $payload);

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
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.batches')
                ->with('info', 'Riwayat batch riwayat studi tersedia di Administrasi Studi Mahasiswa.');
        }

        try {
            $response = $this->apiRequest('get', 'krs-historical/batches', [], ['per_page' => 50]);

            if (!$response->successful()) {
                throw new \RuntimeException($response->json('message') ?? 'Gagal mengambil histori batch riwayat studi historis.');
            }

            return view('akademik.riwayat_studi.batches', [
                'batches' => $response->json('data', []),
                'meta' => $response->json('meta', []),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showBatch(string $id): View|RedirectResponse
    {
        if (!request()->boolean('legacy')) {
            return redirect()
                ->route('akademik.administrasi-studi.batches.show', ['source' => 'historical', 'id' => $id])
                ->with('info', 'Detail batch riwayat studi tersedia di Administrasi Studi Mahasiswa.');
        }

        try {
            $response = $this->apiRequest('get', "krs-historical/batches/{$id}");

            if (!$response->successful()) {
                throw new \RuntimeException($response->json('message') ?? 'Gagal mengambil detail batch riwayat studi historis.');
            }

            return view('akademik.riwayat_studi.show_batch', [
                'batch' => $response->json('data', []),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('akademik.riwayat-studi.batches')->with('error', $e->getMessage());
        }
    }

    public function historicalClasses(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'kelas-kuliah', [], array_filter([
                'id_semester' => $request->query('id_semester'),
                'id_prodi' => $request->query('id_prodi'),
            ], fn($value) => filled($value)));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function packageClasses(Request $request)
    {
        try {
            $response = $this->apiRequest('get', 'krs-historical/package-classes', [], array_filter([
                'id_semester' => $request->query('id_semester'),
                'id_prodi' => $request->query('id_prodi'),
                'semester_ke' => $request->query('semester_ke'),
            ], fn($value) => filled($value)));

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function preparePayload(Request $request, string $action, bool $requireSelected): array
    {
        $payload = [
            'id_semester' => $request->input('historicalSemesterId'),
            'id_prodi' => $request->input('id_prodi'),
            'angkatan' => $request->input('angkatan'),
            'semester_ke' => $request->input('semesterKe'),
            'selected_mahasiswa_ids' => $request->input('selected_mahasiswa_ids', []),
            'notes' => $request->input('batchNotes'),
        ];

        if ($action === 'build_historical_krs') {
            $payload['students_payload'] = $this->decodeStudentsPayload($request->input('students_payload'));
        }

        if ($requireSelected && empty($payload['selected_mahasiswa_ids'])) {
            throw new \InvalidArgumentException('Pilih minimal satu mahasiswa untuk diproses.');
        }

        return array_filter($payload, function ($value, $key) use ($action) {
            if (is_array($value)) {
                return !empty($value);
            }

            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function resolvePreviewEndpoint(string $action): string
    {
        return match ($action) {
            'build_historical_krs' => 'krs-historical/preview/build',
            'reopen_historical_krs' => 'krs-historical/preview/reopen',
            'refinalize_historical_krs' => 'krs-historical/preview/refinalize',
            'reset_historical_krs' => 'krs-historical/preview/reset',
            'generate_khs' => 'krs-historical/preview/generate-khs',
            default => throw new \InvalidArgumentException('Aksi preview historical tidak dikenali.'),
        };
    }

    private function resolveExecuteEndpoint(string $action): string
    {
        return match ($action) {
            'build_historical_krs' => 'krs-historical/execute/build',
            'reopen_historical_krs' => 'krs-historical/execute/reopen',
            'refinalize_historical_krs' => 'krs-historical/execute/refinalize',
            'reset_historical_krs' => 'krs-historical/execute/reset',
            'generate_khs' => 'krs-historical/execute/generate-khs',
            default => throw new \InvalidArgumentException('Aksi execute historical tidak dikenali.'),
        };
    }

    private function apiRequest(string $method, string $endpoint, array $payload = [], array $query = []): Response
    {
        $request = Http::withToken($this->apiToken)
            ->acceptJson();

        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function decodeStudentsPayload(mixed $payload): array
    {
        if (is_array($payload)) {
            return $payload;
        }

        if (!is_string($payload) || trim($payload) === '') {
            return [];
        }

        try {
            $decoded = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \InvalidArgumentException('Payload mahasiswa historis tidak valid.');
        }

        return is_array($decoded) ? $decoded : [];
    }
}
