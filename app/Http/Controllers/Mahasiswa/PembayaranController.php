<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    protected string $apiUrl;

    protected string $apiUrlakad;

    protected string $secret;

    public function __construct()
    {
        $this->apiUrl = config('api.keuangan_url') ?: (env('API_URL_KEUANGAN') ?: 'http://127.0.0.1:8900');
        $this->secret = config('services.internal_api.secret') ?: (env('INTERNAL_API_SECRET') ?: 'super-secret-siakad-555');
        $this->apiUrlakad = config('api.base_url') ?: (env('API_BASE_URL') ?: 'http://127.0.0.1:8800/api/v1/');
    }

    /**
     * Kirim log error ke server Keuangan agar tercatat langsung di log Keu-Siakad
     */
    private function sendErrorLogToKeuangan(string $message, ?string $siswaId = null, $tagihanId = null, $details = null): void
    {
        try {
            Http::timeout(5)->post(rtrim($this->apiUrl, '/') . '/api/payment-error-log', [
                'client_source' => 'SIAKAD_MAHASISWA',
                'message'       => $message,
                'siswa_id'      => $siswaId,
                'tagihan_id'    => $tagihanId,
                'details'       => $details,
                'url'           => request()->fullUrl(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal meneruskan log error ke server keuangan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of bills via API.
     */
    public function index()
    {
        $token = session('access_token');
        $siswaId = null;

        try {
            $res = Http::withToken($token)
                ->acceptJson()
                ->get(rtrim($this->apiUrlakad, '/') . '/auth/me');
            $data = $res->json();
            $siswaId = $data['profile']['id'] ?? null;
        } catch (\Exception $e) {
            Log::error('[SIAKAD Pembayaran] Gagal mengambil profil user dari auth/me', ['error' => $e->getMessage()]);
            $this->sendErrorLogToKeuangan('Gagal auth/me saat akses halaman tagihan', null, null, ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal memverifikasi akun ke server SIAKAD.');
        }

        if (!$siswaId) {
            Log::error('[SIAKAD Pembayaran] Profile ID mahasiswa tidak ditemukan dari auth/me', ['response' => $data ?? null]);
            $this->sendErrorLogToKeuangan('Profile ID mahasiswa tidak ditemukan saat akses tagihan', null, null, $data ?? []);
            return redirect()->back()->with('error', 'Data profil mahasiswa tidak ditemukan.');
        }

        $path = '/api/internal/bills';
        $url = rtrim($this->apiUrl, '/') . $path;

        $queryParams = [
            'siswa_id' => $siswaId,
        ];

        $timestamp = time();
        $method = 'GET';

        // Create HMAC signature
        $bodyForSign = json_encode($queryParams);
        $stringToSign = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => 'siakad',
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
            ])->timeout(10)->get($url, $queryParams);

            if ($response->successful()) {
                $bills = $response->json()['data'] ?? [];
            } else {
                Log::error('Failed to fetch bills from API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'siswa_id' => $siswaId,
                ]);

                $this->sendErrorLogToKeuangan(
                    "Gagal mengambil tagihan dari server keuangan (Status: {$response->status()})",
                    $siswaId,
                    null,
                    [
                        'status' => $response->status(),
                        'response' => $response->json() ?? $response->body(),
                    ]
                );

                $bills = [];
                return redirect()->back()->with('error', 'Gagal mengambil data tagihan dari server.');
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching bills', ['error' => $e->getMessage(), 'siswa_id' => $siswaId]);

            $this->sendErrorLogToKeuangan(
                'Exception saat mengambil tagihan: ' . $e->getMessage(),
                $siswaId,
                null,
                ['error' => $e->getMessage()]
            );

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
        $token = session('access_token');
        $siswaId = null;

        try {
            $res = Http::withToken($token)
                ->acceptJson()
                ->get(rtrim($this->apiUrlakad, '/') . '/auth/me');
            $data = $res->json();
            $siswaId = $data['profile']['id'] ?? null;
        } catch (\Exception $e) {
            Log::error('[SIAKAD Pembayaran] Gagal auth/me saat create pembayaran', ['error' => $e->getMessage()]);
            return redirect()->route('student.pembayaran.index')->with('error', 'Gagal verifikasi akun.');
        }

        $path = '/api/internal/bills';
        $url = rtrim($this->apiUrl, '/') . $path;

        $queryParams = [
            'siswa_id' => $siswaId,
        ];

        $timestamp = time();
        $method = 'GET';

        // Create HMAC signature
        $bodyForSign = json_encode($queryParams);
        $stringToSign = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => 'siakad',
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
            ])->timeout(10)->get($url, $queryParams);

            if ($response->successful()) {
                $bills = $response->json()['data'] ?? [];
                // Find the specific bill by ID
                $tagihan = collect($bills)->firstWhere('id', $tagihanId);

                if (! $tagihan) {
                    $this->sendErrorLogToKeuangan('Tagihan ID ' . $tagihanId . ' tidak ditemukan di daftar tagihan mahasiswa', $siswaId, $tagihanId);
                    return redirect()->route('student.pembayaran.index')
                        ->with('error', 'Tagihan tidak ditemukan.');
                }

                // Convert to object for consistency with view
                $tagihan = json_decode(json_encode($tagihan));
            } else {
                Log::error('Failed to fetch bill details from API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'tagihan_id' => $tagihanId,
                    'siswa_id' => $siswaId,
                ]);

                $this->sendErrorLogToKeuangan(
                    "Gagal mengambil detail tagihan (Status: {$response->status()})",
                    $siswaId,
                    $tagihanId,
                    ['response' => $response->json() ?? $response->body()]
                );

                return redirect()->route('student.pembayaran.index')
                    ->with('error', 'Gagal mengambil data tagihan dari server.');
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching bill details', ['error' => $e->getMessage(), 'tagihan_id' => $tagihanId]);

            $this->sendErrorLogToKeuangan(
                'Exception saat memuat form pembayaran: ' . $e->getMessage(),
                $siswaId,
                $tagihanId,
                ['error' => $e->getMessage()]
            );

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
        $token = session('access_token');
        $siswaId = null;

        try {
            $res = Http::withToken($token)
                ->acceptJson()
                ->get(rtrim($this->apiUrlakad, '/') . '/auth/me');
            $data = $res->json();
            $siswaId = $data['profile']['id'] ?? null;
        } catch (\Exception $e) {
            Log::error('[SIAKAD Pembayaran] Gagal auth/me saat store pembayaran', ['error' => $e->getMessage()]);
            $this->sendErrorLogToKeuangan('Gagal auth/me saat submit pembayaran', null, $tagihanId, ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal verifikasi identitas mahasiswa.');
        }

        // Sanitize jumlah input (remove non-digits in case rupiah formatting is sent)
        if ($request->has('jumlah')) {
            $cleanedJumlah = preg_replace('/[^0-9]/', '', (string) $request->input('jumlah'));
            $request->merge(['jumlah' => $cleanedJumlah !== '' ? (int) $cleanedJumlah : 0]);
        }

        // Validate input first
        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_pembayaran' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $path = "/api/internal/bills/{$tagihanId}/pay";
        $url = rtrim($this->apiUrl, '/') . $path;

        $timestamp = time();
        $method = 'POST';

        // Prepare form data
        $formData = [
            'siswa_id' => (string) $siswaId,
            'jumlah'   => $request->jumlah,
        ];
        if (!empty($request->catatan)) {
            $formData['catatan'] = (string) $request->catatan;
        }

        // Create HMAC signature for POST with form data
        $bodyForSign = json_encode($formData);
        $stringToSign = "{$timestamp}.{$method}.{$path}.{$bodyForSign}";
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        try {
            // Build multipart request
            $http = Http::withHeaders([
                'X-API-KEY' => 'siakad',
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
                Log::info('[SIAKAD Pembayaran Sukses] Pembayaran berhasil dikirim ke Keuangan', [
                    'tagihan_id' => $tagihanId,
                    'siswa_id'   => $siswaId,
                    'jumlah'     => $formData['jumlah'],
                    'response'   => $response->json(),
                ]);

                return redirect()
                    ->route('student.pembayaran.index')
                    ->with('success', 'Pembayaran Anda berhasil dikirim dan sedang menunggu verifikasi admin.');
            } else {
                Log::error('Failed to submit payment to API', [
                    'status'     => $response->status(),
                    'body'       => $response->body(),
                    'tagihan_id' => $tagihanId,
                    'siswa_id'   => $siswaId,
                    'form_data'  => $formData,
                ]);

                // Kirim log error ke server keuangan agar tersimpan di Keu-Siakad/storage/logs/laravel.log
                $this->sendErrorLogToKeuangan(
                    "Gagal submit pembayaran ke API Keuangan (Status: {$response->status()})",
                    $siswaId,
                    $tagihanId,
                    [
                        'status'         => $response->status(),
                        'response_body'  => $response->json() ?? $response->body(),
                        'request_payload'=> $formData,
                    ]
                );

                $errorMessage = 'Gagal mengirim pembayaran ke server.';
                if ($response->status() === 422) {
                    $errors = $response->json()['errors'] ?? [];
                    $errorMessage = collect($errors)->flatten()->first() ?? $errorMessage;
                } elseif ($response->status() === 401) {
                    $errorMessage = 'Gagal otentikasi sistem (HMAC / Key Keuangan tidak valid).';
                } elseif ($response->status() === 404) {
                    $errorMessage = 'Tagihan tidak ditemukan di sistem keuangan.';
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Exception when submitting payment', [
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
                'tagihan_id' => $tagihanId,
                'siswa_id'   => $siswaId,
            ]);

            // Teruskan exception ke log Keuangan
            $this->sendErrorLogToKeuangan(
                'Exception saat submit pembayaran di SIAKAD: ' . $e->getMessage(),
                $siswaId,
                $tagihanId,
                [
                    'exception' => $e->getMessage(),
                    'file'      => $e->getFile() . ':' . $e->getLine(),
                ]
            );

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan koneksi ke server keuangan: ' . $e->getMessage());
        }
    }
}
