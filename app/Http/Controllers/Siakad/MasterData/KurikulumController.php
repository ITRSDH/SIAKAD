<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\DropdownService;

class KurikulumController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function getDatakurikulum()
    {
        try {
            // Ambil data kurikulum dari API
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'kurikulum');
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengambil data All Kurikulum dari API');
            }

            $kurikulum = $response->json()['data'] ?? [];

            // Kirim kedua data ke view
            return response()->json(['data' => $kurikulum]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        return view('masterdata.kurikulum.index');
    }

    public function create(DropdownService $dropdownService)
    {
        try {

            $dropdown = $dropdownService->get('prodi,semester,kurikulum');

            return view('masterdata.kurikulum.create', [
                'prodi' => $dropdown['prodi'] ?? [],
                'semester' => $dropdown['semester'] ?? [],
                'kurikulum' => $dropdown['kurikulum'] ?? [],
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'kurikulum', $request->all());

            if ($response->successful()) {

                // Ambil ID dari response API
                $id = $response->json('data.id');

                return redirect()
                    ->route('kurikulum.detail', $id)
                    ->with('success', 'Data berhasil disimpan');
            }

            return back()->withErrors(
                $response->json('message') ?? 'Gagal menyimpan data'
            );
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    /**
     * Menampilkan detail kurikulum beserta mata kuliahnya.
     */
    public function detail(DropdownService $dropdownService, $id)
    {
        try {
            // Ambil data kurikulum dari API
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$id}");

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengambil data dari API');
            }

            $kurikulum = $response->json('data');

            // Ambil dropdown mata kuliah berdasarkan prodi kurikulum
            $mataKuliahResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$id}/mata-kuliah-list"); // 🔥 Gunakan endpoint baru

            if ($mataKuliahResponse->successful()) {
                $matakuliah = $mataKuliahResponse->json('data.matakuliah', []);
            } else {
                $matakuliah = [];
            }

            // Ambil mata kuliah dari kurikulum (relasi mataKuliah)
            $mataKuliahDiKurikulum = $kurikulum['mata_kuliah'] ?? [];

            // Ambil dropdown kurikulum lain untuk clone (dari prodi yang sama)
            $kurikulumLainResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$id}/kurikulum-list"); // 🔥 Gunakan endpoint baru

            if ($kurikulumLainResponse->successful()) {
                $kurikulumLainRaw = $kurikulumLainResponse->json('data.kurikulum', []);

                // Filter agar kurikulum saat ini tidak ikut
                $kurikulumLain = array_filter($kurikulumLainRaw, function ($item) use ($id) {
                    return $item['id'] !== $id; // Exclude kurikulum saat ini
                });
            } else {
                $kurikulumLain = [];
            }

            $dropdown = $dropdownService->get('prodi,semester,kurikulum');
            $konversiResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . 'konversi-mata-kuliah', [
                    'id_kurikulum_tujuan' => $id,
                ]);

            $konversiMataKuliah = $konversiResponse->successful()
                ? $konversiResponse->json('data', [])
                : [];

            return view('masterdata.kurikulum.detail', [
                'kurikulum' => $kurikulum,
                'matakuliah' => $matakuliah,
                'mataKuliahDiKurikulum' => $mataKuliahDiKurikulum,
                'kurikulum_lain' => $kurikulumLain,
                'konversiMataKuliah' => $konversiMataKuliah,
                'prodi' => $dropdown['prodi'] ?? [],
                'semester' => $dropdown['semester'] ?? [],
                // JANGAN pakai key 'kurikulum' di sini — sudah dipakai untuk data detail.
                'kurikulum_dropdown' => $dropdown['kurikulum'] ?? [],
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function editkolektif($id)
    {
        try {
            // Ambil data kurikulum dari API
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$id}");

            if (!$response->successful()) {
                return back()->withErrors('Gagal mengambil data dari API');
            }

            $kurikulum = $response->json('data');

            // Ambil dropdown mata kuliah berdasarkan prodi kurikulum
            $mataKuliahResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$id}/mata-kuliah-list");

            $matakuliah = $mataKuliahResponse->successful()
                ? $mataKuliahResponse->json('data.matakuliah', [])
                : [];

            // Ambil mata kuliah yang sudah ada di kurikulum (dari data kurikulum yang diambil di atas)
            $mataKuliahDiKurikulum = $kurikulum['mata_kuliah'] ?? [];

            return view('masterdata.kurikulum.edit-kolektif', [
                'kurikulum' => $kurikulum,
                'matakuliah' => $matakuliah,
                'mataKuliahDiKurikulum' => $mataKuliahDiKurikulum, // Tambahkan ini
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Menambahkan mata kuliah ke kurikulum via API.
     */
    public function tambahMataKuliahManual(Request $request, $id_kurikulum)
    {
        try {

            $response = Http::withToken($this->apiToken)
                ->post(
                    $this->apiUrl . "kurikulum/{$id_kurikulum}/tambah-mata-kuliah",
                    $request->all()
                );

            $result = $response->json();


            /*
        |--------------------------------------------------------------------------
        | API berhasil
        |--------------------------------------------------------------------------
        */

            if ($response->successful()) {

                $data = $result['data'] ?? [];

                return redirect()
                    ->back()

                    // Pesan utama
                    ->with(
                        'success',
                        $result['message']
                            ?? 'Mata kuliah berhasil diproses.'
                    )

                    // Status SKS
                    ->with(
                        'sks_status',
                        $data['status'] ?? []
                    )

                    // Target SKS
                    ->with(
                        'sks_target',
                        $data['target'] ?? []
                    )

                    // Total SKS
                    ->with(
                        'sks_total',
                        $data['total'] ?? []
                    )

                    // Kekurangan SKS
                    ->with(
                        'sks_kekurangan',
                        $data['kekurangan'] ?? []
                    )

                    // Mata kuliah berhasil
                    ->with(
                        'mk_berhasil',
                        $data['berhasil'] ?? []
                    )

                    // Mata kuliah ditolak
                    ->with(
                        'mk_ditolak',
                        $data['ditolak'] ?? []
                    )

                    // Duplikat
                    ->with(
                        'mk_duplikat',
                        $data['duplikat'] ?? []
                    );
            }


            /*
        |--------------------------------------------------------------------------
        | API gagal
        |--------------------------------------------------------------------------
        */

            return redirect()
                ->back()
                ->withErrors(
                    $result['message']
                        ?? 'Gagal menambahkan mata kuliah.'
                );
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withErrors($e->getMessage());
        }
    }

    public function tambahMataKuliahManualcheckbox(Request $request, $id_kurikulum)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "kurikulum/{$id_kurikulum}/tambah-mata-kuliah-checkbox", $request->all());

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan.');
            }

            return back()->withErrors($response->json('message', 'Gagal menambahkan mata kuliah.'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function cloneMataKuliah(Request $request, $id_kurikulum_tujuan)
    {
        $request->validate([
            'id_kurikulum_asal' => 'required|string',
        ]);

        try {
            // Validasi existensi kurikulum di API
            $checkResponse = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$request->id_kurikulum_asal}");

            if (!$checkResponse->successful()) {
                return back()->withErrors('Kurikulum asal tidak ditemukan.');
            }

            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . "kurikulum/{$id_kurikulum_tujuan}/clone-mata-kuliah/{$request->id_kurikulum_asal}", []);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Mata kuliah berhasil dikloning.');
            }

            return back()->withErrors($response->json('message', 'Gagal mengkloning mata kuliah.'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Update mata kuliah (pivot) via API.
     */
    public function updateMataKuliah(Request $request, $id_kurikulum, $id_mata_kuliah)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "kurikulum/{$id_kurikulum}/mata-kuliah/{$id_mata_kuliah}", $request->all());

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Mata kuliah berhasil diperbarui.');
            }

            return back()->withErrors($response->json('message', 'Gagal memperbarui mata kuliah.'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Hapus mata kuliah dari kurikulum via API.
     */
    public function hapusMataKuliah(Request $request, $id_kurikulum, $id_mata_kuliah)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "kurikulum/{$id_kurikulum}/mata-kuliah/{$id_mata_kuliah}");

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus.');
            }

            return back()->withErrors($response->json('message', 'Gagal menghapus mata kuliah.'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "kurikulum/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('kurikulum.index')->with('success', 'Data berhasil diupdate');
            }

            return back()->withErrors(
                $response->json('message') ?? 'Gagal mengupdate data'
            )->withInput();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "kurikulum/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menghapus data di API',
                'errors' => $response->json('errors') ?? $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getMataKuliahByKurikulum($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl . "kurikulum/{$id}");

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data kurikulum dari API',
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'data' => $response->json('data.mata_kuliah', []),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeKonversiMataKuliah(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'konversi-mata-kuliah', $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menyimpan konversi mata kuliah',
                'errors' => $response->json('errors') ?? [],
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateKonversiMataKuliah(Request $request, $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "konversi-mata-kuliah/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal memperbarui konversi mata kuliah',
                'errors' => $response->json('errors') ?? [],
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyKonversiMataKuliah($id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "konversi-mata-kuliah/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menghapus konversi mata kuliah',
                'errors' => $response->json('errors') ?? [],
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
