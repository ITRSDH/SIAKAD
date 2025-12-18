<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\DataTableResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PengumumanController extends Controller
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
       return view('admin.master.website.pengumuman.index');
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
                ->get($this->apiUrl . 'pengumuman', $params);

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
                    'judul' => $row['judul'],
                    'isi' => $row['isi'],
                    'kategori' => $row['kategori'],
                    'tanggal' => $row['tanggal'],
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
            $response = Http::withToken($this->apiToken)->post($this->apiUrl . 'pengumuman', $request->all());

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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . "pengumuman/{$id}");

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
            $response = Http::withToken($this->apiToken)->put($this->apiUrl . "pengumuman/{$id}", $request->all());

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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "pengumuman/{$id}");

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
