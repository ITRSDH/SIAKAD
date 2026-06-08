<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KurikulumIndukController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function index(DropdownService $dropdownService)
    {
        try {
            $dropdown = $dropdownService->get('prodi');
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'kurikulum-induk');
            $jenisResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'jenis-kurikulum');

            if (!$response->successful()) {
                return back()->withErrors($response->json('message') ?? 'Gagal mengambil data tahun kurikulum.');
            }

            return view('masterdata.kurikulum_induk.index', [
                'prodi' => $dropdown['prodi'] ?? [],
                'kurikulumInduk' => $response->json('data', []),
                'jenisKurikulum' => $jenisResponse->successful() ? $jenisResponse->json('data', []) : [],
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'kurikulum-induk', $request->all());

            if ($response->successful()) {
                return redirect()->route('kurikulum-induk.index')->with('success', 'Tahun kurikulum berhasil ditambahkan.');
            }

            return back()->withErrors($response->json('message') ?? 'Gagal menyimpan tahun kurikulum.')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "kurikulum-induk/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('kurikulum-induk.index')->with('success', 'Tahun kurikulum berhasil diperbarui.');
            }

            return back()->withErrors($response->json('message') ?? 'Gagal memperbarui tahun kurikulum.')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "kurikulum-induk/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menghapus tahun kurikulum.',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
