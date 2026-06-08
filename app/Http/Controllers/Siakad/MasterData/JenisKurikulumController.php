<?php

namespace App\Http\Controllers\Siakad\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JenisKurikulumController extends Controller
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
        try {
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'jenis-kurikulum');

            if (!$response->successful()) {
                return back()->withErrors($response->json('message') ?? 'Gagal mengambil data jenis kurikulum.');
            }

            return view('masterdata.jenis_kurikulum.index', [
                'jenisKurikulum' => $response->json('data', []),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl . 'jenis-kurikulum', $request->all());

            if ($response->successful()) {
                return redirect()->route('jenis-kurikulum.index')->with('success', 'Jenis kurikulum berhasil ditambahkan.');
            }

            return back()->withErrors($response->json('message') ?? 'Gagal menyimpan jenis kurikulum.')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->put($this->apiUrl . "jenis-kurikulum/{$id}", $request->all());

            if ($response->successful()) {
                return redirect()->route('jenis-kurikulum.index')->with('success', 'Jenis kurikulum berhasil diperbarui.');
            }

            return back()->withErrors($response->json('message') ?? 'Gagal memperbarui jenis kurikulum.')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->delete($this->apiUrl . "jenis-kurikulum/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal menghapus jenis kurikulum.',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
