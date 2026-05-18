<?php

namespace App\Http\Controllers\Siakad\AkhirStudi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class MonitoringAkhirStudiController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index(Request $request)
    {
        try {
            $tugasAkhirResponse = $this->apiRequest('tugas-akhir');
            $yudisiumResponse = $this->apiRequest('yudisium');
            $kelulusanResponse = $this->apiRequest('kelulusan');
            $periodeWisudaResponse = $this->apiRequest('wisuda/periode');

            if (
                !$tugasAkhirResponse->successful() ||
                !$yudisiumResponse->successful() ||
                !$kelulusanResponse->successful() ||
                !$periodeWisudaResponse->successful()
            ) {
                return back()->with('error', 'Gagal mengambil data monitoring akhir studi dari API.');
            }

            $tugasAkhir = collect($tugasAkhirResponse->json()['data'] ?? []);
            $yudisium = collect($yudisiumResponse->json()['data'] ?? []);
            $kelulusan = collect($kelulusanResponse->json()['data'] ?? []);
            $periodeWisuda = collect($periodeWisudaResponse->json()['data'] ?? []);

            $filters = [
                'tugas_akhir_status' => (string) $request->query('tugas_akhir_status', ''),
                'yudisium_status' => (string) $request->query('yudisium_status', ''),
                'kelulusan_status' => (string) $request->query('kelulusan_status', ''),
                'wisuda_status' => (string) $request->query('wisuda_status', ''),
                'search' => trim((string) $request->query('search', '')),
            ];

            $tugasAkhir = $this->filterTugasAkhir($tugasAkhir, $filters);
            $yudisium = $this->filterYudisium($yudisium, $filters);
            $kelulusan = $this->filterKelulusan($kelulusan, $filters);
            $periodeWisuda = $this->filterPeriodeWisuda($periodeWisuda, $filters);

            $summary = [
                'tugas_akhir_total' => $tugasAkhir->count(),
                'tugas_akhir_lulus' => $tugasAkhir->filter(fn (array $item) => $this->normalizeStatus($item['status'] ?? null) === 'lulus')->count(),
                'yudisium_memenuhi' => $yudisium->filter(fn (array $item) => $this->normalizeStatus($item['status'] ?? null) === 'memenuhi')->count(),
                'kelulusan_ditetapkan' => $kelulusan->filter(fn (array $item) => $this->normalizeStatus($item['status'] ?? null) === 'ditetapkan')->count(),
                'periode_wisuda_aktif' => $periodeWisuda->filter(fn (array $item) => in_array($this->normalizeStatus($item['status'] ?? null), ['draft', 'dibuka'], true))->count(),
                'peserta_wisuda_total' => $periodeWisuda->sum(fn (array $item) => (int) ($item['peserta_count'] ?? 0)),
            ];

            $statusBreakdown = [
                'tugas_akhir' => $this->buildStatusBreakdown($tugasAkhir, ['pengajuan', 'bimbingan', 'ujian', 'lulus', 'revisi', 'draft']),
                'yudisium' => $this->buildStatusBreakdown($yudisium, ['memenuhi', 'belum_memenuhi']),
                'kelulusan' => $this->buildStatusBreakdown($kelulusan, ['draft', 'ditetapkan']),
                'wisuda' => $this->buildStatusBreakdown($periodeWisuda, ['draft', 'dibuka', 'ditutup', 'selesai']),
            ];

            $recent = [
                'tugas_akhir' => $tugasAkhir->take(5),
                'yudisium' => $yudisium->take(5),
                'kelulusan' => $kelulusan->take(5),
                'wisuda' => $periodeWisuda->take(5),
            ];

            return view('akhir_studi.monitoring.index', compact(
                'summary',
                'statusBreakdown',
                'recent',
                'periodeWisuda',
                'filters'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function apiRequest(string $endpoint): Response
    {
        return Http::withToken(session('access_token'))
            ->acceptJson()
            ->get(rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/'));
    }

    private function buildStatusBreakdown(Collection $items, array $orderedStatuses): array
    {
        $counts = $items
            ->groupBy(fn (array $item) => $this->normalizeStatus($item['status'] ?? null))
            ->map(fn (Collection $group) => $group->count())
            ->all();

        $result = [];
        foreach ($orderedStatuses as $status) {
            $result[$status] = $counts[$status] ?? 0;
        }

        foreach ($counts as $status => $count) {
            if (!array_key_exists($status, $result)) {
                $result[$status] = $count;
            }
        }

        return $result;
    }

    private function filterTugasAkhir(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['tugas_akhir_status']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['status'] ?? '') === $filters['tugas_akhir_status']
                    || $this->normalizeStatus($item['status'] ?? null) === $filters['tugas_akhir_status']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['mahasiswa']['nama_mahasiswa'] ?? ''),
                    (string) ($item['mahasiswa']['nim'] ?? ''),
                    (string) ($item['judul'] ?? ''),
                    (string) ($item['topik'] ?? ''),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }

    private function filterYudisium(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['yudisium_status']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['status'] ?? '') === $filters['yudisium_status']
                    || $this->normalizeStatus($item['status'] ?? null) === $filters['yudisium_status']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['mahasiswa']['nama_mahasiswa'] ?? ''),
                    (string) ($item['mahasiswa']['nim'] ?? ''),
                    (string) ($item['predikat_lulus'] ?? ''),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }

    private function filterKelulusan(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['kelulusan_status']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['status'] ?? '') === $filters['kelulusan_status']
                    || $this->normalizeStatus($item['status'] ?? null) === $filters['kelulusan_status']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['mahasiswa']['nama_mahasiswa'] ?? ''),
                    (string) ($item['mahasiswa']['nim'] ?? ''),
                    (string) ($item['nomor_ijazah'] ?? ''),
                    (string) ($item['nomor_sk'] ?? ''),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }

    private function filterPeriodeWisuda(Collection $items, array $filters): Collection
    {
        return $items
            ->when(filled($filters['wisuda_status']), fn (Collection $collection) => $collection->filter(
                fn (array $item) => (string) ($item['status'] ?? '') === $filters['wisuda_status']
                    || $this->normalizeStatus($item['status'] ?? null) === $filters['wisuda_status']
            ))
            ->when(filled($filters['search']), fn (Collection $collection) => $collection->filter(function (array $item) use ($filters) {
                $needle = strtolower($filters['search']);
                $haystack = strtolower(implode(' ', [
                    (string) ($item['nama_periode'] ?? ''),
                    (string) ($item['lokasi'] ?? ''),
                    (string) ($item['catatan'] ?? ''),
                ]));

                return str_contains($haystack, $needle);
            }))
            ->values();
    }

    private function normalizeStatus(mixed $status): string
    {
        $value = strtolower(trim((string) $status));

        return $value !== '' ? $value : 'draft';
    }
}
