<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\DataTableResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeritaController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index()
    {
        return view('admin.master.website.berita.index');
    }

    /**
     * =========================================
     * 2️⃣ DataTables Server-Side (AJAX)
     * =========================================
     */
    public function datatable(Request $request)
    {
        try {
            $page = $request->start / $request->length + 1;
            $perPage = $request->length;
            $search = $request->search['value'] ?? null;

            $params = [
                'page' => $page,
                'per_page' => $perPage,
            ];

            // Tambahkan parameter search jika ada
            if (!empty($search)) {
                $params['search'] = $search;
            }

            $response = Http::withToken($this->apiToken)
                ->timeout(10)
                ->retry(2, 200)
                ->get($this->apiUrl . 'berita', $params);

            if (!$response->successful()) {
                return DataTableResponse::empty($request);
            }

            $json = $response->json();

            // ⬇️ INI KUNCI UTAMA
            if (!isset($json['data']['data'])) {
                return DataTableResponse::empty($request);
            }

            $payload = $json['data'];

            return DataTableResponse::fromApi(
                $request,
                $payload,
                fn($row, $i, $request) => [
                    'DT_RowIndex' => $request->start + $i + 1,
                    'gambar' => $row['gambar'] ?? null,
                    'judul' => $row['judul'] ?? null,
                    'kategori' => $row['kategori'] ?? null,
                    'tanggal' => $row['tanggal'] ?? null,
                    'isi' => $row['isi'] ?? null,
                    'aksi' => '
                                <div class="d-flex justify-content-center gap-1">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning btn-icon edit-btn"
                                        data-id="'.$row['id'].'"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger btn-icon delete-btn"
                                        data-id="'.$row['id'].'"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            ',

                ],
            );
        } catch (\Throwable $e) {
            return DataTableResponse::empty($request);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'kategori' => 'nullable|string|max:100',
                'gambar' => 'nullable|file|max:2048',
                'tanggal' => 'nullable|date',
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['judul', 'isi', 'kategori', 'tanggal']);

            // Jika ada file gambar, siapkan untuk multipart/form-data
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('gambar', file_get_contents($request->file('gambar')), $request->file('gambar')->getClientOriginalName())
                    ->post($this->apiUrl . 'berita', $data);
            } else {
                // Jika tidak ada file, kirim sebagai JSON biasa
                $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'berita', $data);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke API',
                    'errors' => $response->json(),
                ],
                422,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "berita/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'kategori' => 'nullable|string|max:100',
                'gambar' => 'nullable|file|max:2048',
                'tanggal' => 'nullable|date',
            ]);

            // Buat data untuk dikirim ke API
            $data = $request->only(['judul', 'isi', 'kategori', 'tanggal']);

            // Handle file upload dengan attach jika ada file
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($this->apiToken)
                    ->attach('gambar', file_get_contents($request->file('gambar')), $request->file('gambar')->getClientOriginalName())
                    ->post($this->apiUrl . "berita/{$id}?_method=PUT", $data);
            } else {
                $response = Http::withToken($this->apiToken)->put($this->apiUrl . "berita/{$id}", $data);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal memperbarui data di API',
                    'errors' => $response->json(),
                ],
                422,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "berita/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data di API',
                    'errors' => $response->json(),
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
