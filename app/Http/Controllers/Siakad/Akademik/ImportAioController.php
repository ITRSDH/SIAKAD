<?php

namespace App\Http\Controllers\Siakad\Akademik;

use App\Http\Controllers\Controller;
use App\Services\DropdownService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportAioController extends Controller
{
    protected string $apiUrl;

    protected ?string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    /**
     * Tampilan utama menu Import AIO
     */
    public function index(DropdownService $dropdownService): View
    {
        $options = [];
        try {
            $token = $this->getToken();
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(rtrim($this->apiUrl, '/').'/import-aio/options');

            if ($response->successful()) {
                $options = $response->json('data', []);
            }
        } catch (\Throwable $e) {
            // fallback
        }

        // Fallback ke DropdownService jika opsi kosong
        if (empty($options['semesters']) || empty($options['prodis'])) {
            try {
                $dropdown = $dropdownService->get('prodi,semester,kurikulum');
                if (empty($options['prodis']) && ! empty($dropdown['prodi'])) {
                    $options['prodis'] = collect($dropdown['prodi'])->map(fn ($p) => [
                        'id' => $p['id'],
                        'nama_prodi' => $p['prodi'] ?? ($p['nama_prodi'] ?? 'Prodi'),
                    ])->all();
                }
                if (empty($options['semesters']) && ! empty($dropdown['semester'])) {
                    $options['semesters'] = collect($dropdown['semester'])->map(fn ($s) => [
                        'id' => $s['id'],
                        'nama_semester' => $s['semester'] ?? ($s['nama_semester'] ?? 'Semester'),
                    ])->all();
                }
                if (empty($options['kurikulums']) && ! empty($dropdown['kurikulum'])) {
                    $options['kurikulums'] = collect($dropdown['kurikulum'])->map(fn ($k) => [
                        'id' => $k['id'],
                        'id_prodi' => $k['id_prodi'] ?? '',
                        'nama_struktur_mk' => $k['kurikulum'] ?? ($k['nama_struktur_mk'] ?? 'Kurikulum'),
                    ])->all();
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        \Illuminate\Support\Facades\Log::info('ImportAioController@index called.', [
            'token_exists' => filled($this->getToken()),
            'semesters_count' => count($options['semesters'] ?? []),
            'prodis_count' => count($options['prodis'] ?? []),
            'kurikulums_count' => count($options['kurikulums'] ?? []),
        ]);

        $angkatans = $options['angkatans'] ?? [];
        if (empty($angkatans)) {
            $currentYear = (int) date('Y');
            $angkatans = range($currentYear, $currentYear - 6);
        }
        $jalurs = $options['jalurs'] ?? ['Reguler', 'RPL'];

        return view('akademik.import_aio.index', [
            'options' => $options,
            'semesters' => $options['semesters'] ?? [],
            'prodis' => $options['prodis'] ?? [],
            'kurikulums' => $options['kurikulums'] ?? [],
            'angkatans' => $angkatans,
            'jalurs' => $jalurs,
        ]);
    }

    /**
     * Unduh template contoh Excel AIO
     */
    public function template(): BinaryFileResponse
    {
        $localPath = public_path('template_import_aio_contoh.xlsx');
        if (file_exists($localPath)) {
            return response()->download($localPath, 'Template_Import_AIO_Siakad.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        abort(404, 'File template import AIO belum tersedia di server.');
    }

    /**
     * Ambil daftar mata kuliah untuk filter template AIO
     */
    public function templateSubjects(Request $request): JsonResponse
    {
        $token = $this->getToken();
        $query = [
            'id_kurikulum' => $request->query('id_kurikulum'),
            'id_prodi' => $request->query('id_prodi'),
            'semester_ke' => $request->query('semester_ke', 1),
            'jenis_pendaftaran' => $request->query('jenis_pendaftaran') ?? $request->query('jalur'),
        ];

        try {
            $response = Http::withToken($token)
                ->get(rtrim($this->apiUrl, '/').'/import-aio/template-subjects', array_filter($query));

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unduh template dinamis sesuai filter (Semester, Prodi, Kurikulum, Angkatan, Jalur)
     */
    public function templateFiltered(Request $request)
    {
        $token = $this->getToken();
        $query = [
            'id_semester' => $request->query('id_semester'),
            'id_prodi' => $request->query('id_prodi'),
            'id_kurikulum' => $request->query('id_kurikulum'),
            'angkatan' => $request->query('angkatan'),
            'jenis_pendaftaran' => $request->query('jenis_pendaftaran') ?? $request->query('jalur'),
            'semester_ke' => $request->query('semester_ke', 1),
            'subject_order' => $request->query('subject_order'),
        ];

        try {
            $response = Http::withToken($token)
                ->get(rtrim($this->apiUrl, '/').'/import-aio/template-filtered', array_filter($query));

            if (! $response->successful()) {
                return back()->with('error', $response->json('message') ?? 'Gagal membuat template sesuai filter.');
            }

            $contentType = $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $disposition = $response->header('Content-Disposition');
            $filename = 'Template_AIO_Filtered.xlsx';

            if (preg_match('/filename=\"?([^\";]+)\"?/i', (string) $disposition, $matches)) {
                $filename = trim($matches[1], ' "\'');
            }

            return response($response->body())
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengunduh template: '.$e->getMessage());
        }
    }

    /**
     * Kirim file Excel ke API untuk diproses
     */
    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:20480',
            'id_semester' => 'nullable|string',
            'id_prodi' => 'nullable|string',
            'id_kurikulum' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $token = $this->getToken();

            $httpRequest = Http::withToken($token)
                ->timeout(300)
                ->acceptJson()
                ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName());

            $postData = [];
            if ($request->filled('id_semester')) {
                $postData['id_semester'] = $request->input('id_semester');
            }
            if ($request->filled('id_prodi')) {
                $postData['id_prodi'] = $request->input('id_prodi');
            }
            if ($request->filled('id_kurikulum')) {
                $postData['id_kurikulum'] = $request->input('id_kurikulum');
            }

            $response = $httpRequest->post(
                rtrim($this->apiUrl, '/').'/import-aio/process',
                $postData
            );

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file import: '.$e->getMessage(),
            ], 500);
        }
    }

    private function getToken(): ?string
    {
        return $this->apiToken ?: session('access_token');
    }
}
