<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\DropdownService;

class KelaskuliahController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getDatakelaskuliah()
    {
        try {
            // Ambil data kelaskuliah dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'kelas-kuliah');
            if (!$response->successful()) {
                return response()->json([
                    'data' => [],
                    'message' => $response->json('message') ?? 'Data kelas kuliah tidak tersedia.',
                ]);
            }

            $kelaskuliah = $response->json('data');

            if (!is_array($kelaskuliah)) {
                $kelaskuliah = [];
            }

            // Kirim kedua data ke view
            return response()->json(['data' => $kelaskuliah]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function index()
    {
        return view('masterdata.kelaskuliah.index');
    }

    public function create(DropdownService $dropdownService)
    {
        try {

            $dropdown = $dropdownService->get('prodi,semester,kurikulum_matakuliah');

            return view('masterdata.kelaskuliah.create', [
                'prodi' => $dropdown['prodi'] ?? [],
                'semester' => $dropdown['semester'] ?? [],
                'kurikulum_matakuliah' => $dropdown['kurikulum_matakuliah'] ?? []
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function detail(DropdownService $dropdownService, $id)
    {
        try {
            // Ambil data kelas-kuliah dari API
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kelas-kuliah/{$id}");

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengambil data dari API');
            }

            $kelaskuliah = $response->json('data');
            $pesertaKrs = $this->fetchPesertaKrsByKelas($id);
            $krsCandidates = $this->fetchKrsCandidatesByKelas($id);

            $dropdown = $dropdownService->get('prodi,semester,kurikulum_matakuliah');
            return view('masterdata.kelaskuliah.detail', [
                'kelaskuliah' => $kelaskuliah,
                'pesertaKrs' => $pesertaKrs,
                'krsCandidates' => $krsCandidates['rows'],
                'krsCandidateSummary' => $krsCandidates['summary'],
                'prodi' => $dropdown['prodi'] ?? [],
                'semester' => $dropdown['semester'] ?? [],
                'kurikulum_matakuliah' => $dropdown['kurikulum_matakuliah'] ?? []
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'kelas-kuliah', $request->all());

            if ($response->successful()) {

                // Ambil ID dari response API
                $id = $response->json('data.id');

                return redirect()
                    ->route('kelas-kuliah.detail', $id)
                    ->with('success', 'Data berhasil disimpan');
            }

            return back()->withErrors(
                $response->json('message') ?? 'Gagal menyimpan data'
            );
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "kelas-kuliah/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()
                    ->route('kelas-kuliah.detail', $id)
                    ->with('success', 'Data berhasil diubah');
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data di API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "kelas-kuliah/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data di API',
                'errors' => $response->json()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function registerKrsMahasiswa(Request $request, string $id)
    {
        $validated = $request->validate([
            'mahasiswa_ids' => 'required|array|min:1',
            'mahasiswa_ids.*' => 'required|string',
        ], [
            'mahasiswa_ids.required' => 'Pilih minimal satu mahasiswa untuk didaftarkan.',
            'mahasiswa_ids.min' => 'Pilih minimal satu mahasiswa untuk didaftarkan.',
        ]);

        try {
            $response = Http::withToken($this->apiToken)
                ->acceptJson()
                ->post($this->apiUrl . "kelas-kuliah/{$id}/register-krs", [
                    'mahasiswa_ids' => $validated['mahasiswa_ids'],
                ]);

            $payload = $response->json();

            if (!$response->successful() || !($payload['success'] ?? false)) {
                return back()->withErrors($payload['message'] ?? 'Gagal mendaftarkan mahasiswa ke KRS.');
            }

            $data = $payload['data'] ?? [];
            $registered = (int) ($data['registered_count'] ?? 0);
            $already = (int) ($data['already_registered_count'] ?? 0);
            $failed = (int) ($data['failed_count'] ?? 0);

            $message = "Pendaftaran selesai. {$registered} mahasiswa berhasil didaftarkan";
            if ($already > 0) {
                $message .= ", {$already} sudah terdaftar";
            }
            if ($failed > 0) {
                $message .= ", {$failed} belum bisa diproses";
            }
            $message .= '.';

            return redirect()
                ->route('kelas-kuliah.detail', $id)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    private function fetchPesertaKrsByKelas(string $kelasKuliahId): array
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->acceptJson()
                ->get($this->apiUrl . "penilaian/kelas/{$kelasKuliahId}/nilai");

            if (!$response->successful()) {
                return [];
            }

            $payload = $response->json('data', []);
            $items = $payload['mahasiswa'] ?? (is_array($payload) ? $payload : []);

            if (!is_array($items)) {
                return [];
            }

            $participants = collect($items)
                ->map(function ($item) {
                    $mahasiswa = $item['mahasiswa'] ?? $item;
                    $presensi = $item['presensi_summary'] ?? [];
                    $finalScore = $item['nilai_akhir_existing'] ?? $item;

                    return [
                        'id' => $mahasiswa['id'] ?? $item['id_mahasiswa'] ?? null,
                        'nim' => $mahasiswa['nim'] ?? '-',
                        'nama_mahasiswa' => $mahasiswa['nama_mahasiswa'] ?? '-',
                        'angkatan' => $mahasiswa['angkatan'] ?? '-',
                        'status_krs' => $item['status_kelayakan'] ?? $item['status_penilaian'] ?? $item['status'] ?? 'terdaftar',
                        'persentase_presensi' => $presensi['persentase_presensi'] ?? $item['persentase_presensi'] ?? $item['presensi_persen'] ?? null,
                        'nilai_akhir' => $finalScore['nilai_akhir'] ?? $item['nilai_akhir'] ?? null,
                        'nilai_huruf' => $finalScore['nilai_huruf'] ?? $item['nilai_huruf'] ?? null,
                    ];
                })
                ->filter(fn ($item) => filled($item['id']) || filled($item['nim']) || filled($item['nama_mahasiswa']))
                ->unique(fn ($item) => (string) ($item['id'] ?? $item['nim'] ?? $item['nama_mahasiswa']))
                ->sortBy('nama_mahasiswa', SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all();

            return $participants;
        } catch (\Throwable $exception) {
            return [];
        }
    }

    private function fetchKrsCandidatesByKelas(string $kelasKuliahId): array
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->acceptJson()
                ->get($this->apiUrl . "kelas-kuliah/{$kelasKuliahId}/krs-candidates");

            if (!$response->successful()) {
                return [
                    'rows' => [],
                    'summary' => [],
                ];
            }

            return [
                'rows' => $response->json('data', []),
                'summary' => $response->json('meta.summary', []),
            ];
        } catch (\Throwable $exception) {
            return [
                'rows' => [],
                'summary' => [],
            ];
        }
    }
}
