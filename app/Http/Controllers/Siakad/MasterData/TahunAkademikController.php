<?php

namespace App\Http\Controllers\Siakad\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TahunAkademikController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }
    /**
     * ============================
     * LIST TAHUN AKADEMIK
     * ============================
     */
    public function index()
    {
        $response = Http::withToken($this->apiToken)
            ->get($this->apiUrl . 'tahun-akademik');

        abort_if(!$response->successful(), 500, 'Gagal mengambil data');

        return view('masterdata.tahun_akademik.index', [
            'data' => $response->json('data')
        ]);
    }

    /**
     * ============================
     * FORM CREATE
     * ============================
     */
    public function create()
    {
        return view('masterdata.tahun_akademik.create');
    }

    /**
     * ============================
     * STORE
     * ============================
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required',
            'semester' => 'required|array|min:1',
        ]);

        $response = Http::withToken($this->apiToken)
            ->post($this->apiUrl . 'tahun-akademik', $request->all());

        return $response->successful()
            ? redirect()->route('tahun-akademik.index')->with('success', 'Data berhasil disimpan')
            : back()->withErrors($response->json('message'));
    }

    /**
     * ============================
     * DETAIL / EDIT
     * ============================
     */
    public function edit(string $id)
    {
        $response = Http::withToken($this->apiToken)
            ->get($this->apiUrl . "tahun-akademik/{$id}");

        abort_if(!$response->successful(), 404);

        return view('masterdata.tahun_akademik.edit', [
            'data' => $response->json('data')
        ]);
    }

    /**
     * ============================
     * UPDATE
     * ============================
     */
    public function update(Request $request, string $id)
    {
        $response = Http::withToken($this->apiToken)
            ->put($this->apiUrl . "tahun-akademik/{$id}", $request->all());

        return $response->successful()
            ? redirect()->route('tahun-akademik.index')->with('success', 'Data berhasil diupdate')
            : back()->withErrors($response->json('message'));
    }

    /**
     * ============================
     * DELETE
     * ============================
     */
    public function destroy(string $id)
    {
        $response = Http::withToken($this->apiToken)
            ->delete($this->apiUrl . "tahun-akademik/{$id}");

        return back()->with(
            $response->successful() ? 'success' : 'error',
            $response->json('message')
        );
    }

    /**
     * ============================
     * SET TAHUN AKTIF
     * ============================
     */
    public function setTahunAktif(string $id)
    {
        $response = Http::withToken($this->apiToken)
            ->put($this->apiUrl . "tahun-akademik/tahun-aktif/{$id}");

        return back()->with('success', $response->json('message'));
    }

    /**
     * ============================
     * SET SEMESTER AKTIF
     * ============================
     */
    public function setSemesterAktif(string $id)
    {
        $response = Http::withToken($this->apiToken)
            ->put($this->apiUrl . "tahun-akademik/semester-aktif/{$id}");

        // ❌ Jika API menolak (422 / 500)
        if (!$response->successful()) {
            return back()->withErrors(
                $response->json('message')
                    ?? 'Tidak dapat mengaktifkan semester'
            );
        }

        // ✅ Jika berhasil
        return back()->with('success', $response->json('message'));
    }
}
