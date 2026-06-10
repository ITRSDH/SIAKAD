<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class KhsController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function show(string $khsId): View|RedirectResponse
    {
        try {
            $response = $this->apiRequest('get', "khs/{$khsId}");
            if (!$response->successful()) {
                return redirect()->route('akademik.khs.import.history')
                    ->with('error', $response->json('message') ?? 'Gagal mengambil detail KHS.');
            }

            $khs = $response->json('data', []);
            $batchHistories = $this->loadBatchHistories($khs['details'] ?? []);
            $revisionItems = collect($batchHistories)
                ->flatMap(function (array $batch) {
                    $batchId = $batch['id'] ?? null;
                    $fileName = $batch['file_name'] ?? 'Batch Import';

                    return collect($batch['revisions'] ?? [])->map(function (array $revision) use ($batchId, $fileName) {
                        $revision['batch_id'] = $batchId;
                        $revision['batch_file_name'] = $fileName;

                        return $revision;
                    });
                })
                ->sortByDesc(fn(array $item) => $item['created_at'] ?? '')
                ->values()
                ->all();

            return view('akademik.khs.show', compact('khs', 'batchHistories', 'revisionItems'));
        } catch (\Exception $e) {
            return redirect()->route('akademik.khs.import.history')->with('error', $e->getMessage());
        }
    }

    public function updateDetail(Request $request, string $khsId, string $detailId)
    {
        $validated = $request->validate([
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'nilai_huruf' => 'nullable|string|max:2',
            'bobot_nilai' => 'nullable|numeric|min:0',
            'mutu' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $response = $this->apiRequest('put', "khs/{$khsId}/details/{$detailId}", $validated);

            return response()->json(
                $response->json(),
                $response->status()
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateSummary(Request $request, string $khsId)
    {
        $validated = $request->validate([
            'ipk' => 'nullable|numeric|min:0|max:4',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $response = $this->apiRequest('put', "khs/{$khsId}/summary", $validated);

            return response()->json(
                $response->json(),
                $response->status()
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function finalize(Request $request, string $khsId): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $response = $this->apiRequest('post', "khs/{$khsId}/finalize", $validated);

            if (!$response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal melakukan finalisasi KHS.');
            }

            return back()->with('success', $response->json('message') ?? 'KHS berhasil difinalisasi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function apiRequest(string $method, string $endpoint, array $payload = [], array $query = []): Response
    {
        $request = Http::withToken($this->apiToken)
            ->acceptJson();

        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');

        return match (strtolower($method)) {
            'get' => $request->get($url, $query),
            'post' => $request->post($url, $payload),
            'put' => $request->put($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }

    private function loadBatchHistories(array $details): array
    {
        return collect($details)
            ->pluck('id_import_batch')
            ->filter()
            ->unique()
            ->map(function (string $batchId) {
                $response = $this->apiRequest('get', "khs/import/{$batchId}");

                if (!$response->successful()) {
                    return null;
                }

                return $response->json('data', []);
            })
            ->filter(fn($item) => is_array($item) && !empty($item))
            ->values()
            ->all();
    }
}
