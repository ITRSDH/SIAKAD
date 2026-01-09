<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    protected string $apiUrl;
    protected string $secret;

    public function __construct()
    {
        $this->apiUrl = config('api.keuangan_url', 'http://localhost:8001');
        $this->secret = config('services.internal_api.secret', 'siakad_keuangan_secret_2026');
    }

    /**
     * Display a listing of bills via API.
     */
    public function index()
    {
        // TODO: Get from authenticated user session
        $siswaId = '7eb11250-75be-41ff-8b2c-fb418f68b128';

        $path = '/api/internal/bills';
        $url  = $this->apiUrl . $path;

        $queryParams = [
            'siswa_id' => $siswaId,
        ];

        $timestamp = time();
        $method    = 'GET';

        // Create HMAC signature
        $bodyForSign = json_encode($queryParams);
        $stringToSign = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        try {
            $response = Http::withHeaders([
                'X-API-KEY'   => 'siakad',
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
            ])->timeout(10)->get($url, $queryParams);
             

            if ($response->successful()) {
                $bills = $response->json()['data'] ?? [];

            } else {
                Log::error('Failed to fetch bills from API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $bills = [];
                return redirect()->back()->with('error', 'Gagal mengambil data tagihan dari server.');
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching bills', ['error' => $e->getMessage()]);
            $bills = [];
            return redirect()->back()->with('error', 'Terjadi kesalahan koneksi ke server keuangan.');
        }

        return view('mahasiswa.pembayaran.index', compact('bills'));
    }

    /**
     * Show form to pay a bill.
     */
    public function create($tagihanId)
    {
        // TODO: Get from authenticated user session
        $siswaId = '7eb11250-75be-41ff-8b2c-fb418f68b128';

        $path = '/api/internal/bills';
        $url  = $this->apiUrl . $path;

        $queryParams = [
            'siswa_id' => $siswaId,
        ];

        $timestamp = time();
        $method    = 'GET';

        // Create HMAC signature
        $bodyForSign = json_encode($queryParams);
        $stringToSign = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        try {
            $response = Http::withHeaders([
                'X-API-KEY'   => 'siakad',
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
            ])->timeout(10)->get($url, $queryParams);

            if ($response->successful()) {
                $bills = $response->json()['data'] ?? [];
                // Find the specific bill by ID
                $tagihan = collect($bills)->firstWhere('id', $tagihanId);
                
                if (!$tagihan) {
                    return redirect()->route('student.pembayaran.index')
                        ->with('error', 'Tagihan tidak ditemukan.');
                }

                // Convert to object for consistency with view
                $tagihan = json_decode(json_encode($tagihan));
            } else {
                Log::error('Failed to fetch bill details from API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->route('student.pembayaran.index')
                    ->with('error', 'Gagal mengambil data tagihan dari server.');
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching bill details', ['error' => $e->getMessage()]);
            return redirect()->route('student.pembayaran.index')
                ->with('error', 'Terjadi kesalahan koneksi ke server keuangan.');
        }

        return view('mahasiswa.pembayaran.create', compact('tagihan'));
    }

    /**
     * Submit payment to API.
     */
    public function store(Request $request, $tagihanId)
    {
        // TODO: Get from authenticated user session
        $siswaId = '7eb11250-75be-41ff-8b2c-fb418f68b128';

        // Validate input first
        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_pembayaran' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $path = "/api/internal/bills/{$tagihanId}/pay";
        $url  = $this->apiUrl . $path;

        $timestamp = time();
        $method    = 'POST';

        // Prepare form data
        $formData = [
            'siswa_id' => $siswaId,
            'jumlah' => $request->jumlah,
        ];

        // Create HMAC signature for POST with form data
        $bodyForSign = json_encode($formData);
        $stringToSign = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        try {
            // Build multipart request
            $http = Http::withHeaders([
                'X-API-KEY'   => 'siakad',
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
            ])->timeout(30);

            // Attach file if present
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $http->attach(
                    'bukti_pembayaran',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            // Send POST request
            $response = $http->post($url, $formData);

            if ($response->successful()) {
                return redirect()
                    ->route('student.pembayaran.index')
                    ->with('success', 'Pembayaran Anda berhasil dikirim dan sedang menunggu verifikasi admin.');
            } else {
                Log::error('Failed to submit payment to API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                $errorMessage = 'Gagal mengirim pembayaran ke server.';
                if ($response->status() === 422) {
                    $errors = $response->json()['errors'] ?? [];
                    $errorMessage = collect($errors)->flatten()->first() ?? $errorMessage;
                }
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Exception when submitting payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan koneksi ke server keuangan.');
        }
    }
}
