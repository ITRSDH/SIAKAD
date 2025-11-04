<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AutoRefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected string $apiUrl;

    // Waktu sebelum kadaluarsa (dalam detik) untuk memicu refresh otomatis.
    // Misalnya: 55 menit = 55 * 60 = 3300 detik
    // Jika waktu sekarang >= (expires_at - 300 detik), maka refresh.
    private int $refreshThresholdSeconds = 300; // 5 menit sebelum kadaluarsa

    public function __construct()
    {
        $this->apiUrl = config('api.base_url'); // Misal: https://api.yoursite.com/
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('access_token') && Session::has('expires_at')) {
            $expiresAt = session('expires_at');
            $now = time();
            $thresholdTime = $expiresAt - $this->refreshThresholdSeconds;

            // Jika waktu sekarang sudah melewati ambang batas (threshold), refresh token
            if ($now >= $thresholdTime) {
                $this->refreshAccessToken();
            }
        }

        return $next($request);
    }

    private function refreshAccessToken()
    {
        try {
            $response = Http::withHeaders([
                // Jika API membutuhkan header tambahan seperti Content-Type, tambahkan di sini
                'Authorization' => 'Bearer ' . session('access_token'), // Jika refresh membutuhkan access_token lama
            ])->post($this->apiUrl . 'auth/refresh', [
                'refresh_token' => session('refresh_token')
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Pastikan API mengembalikan struktur data yang sesuai
                if (isset($data['success']) && $data['success'] === true && isset($data['data'])) {
                    $tokenData = $data['data'];

                    // Perbarui session dengan token baru
                    session([
                        'access_token' => $tokenData['access_token'],
                        'refresh_token' => $tokenData['refresh_token'], // API bisa saja mengembalikan refresh token baru
                        'expires_at' => time() + $tokenData['expires_in'],
                    ]);
                } else {
                    // Jika struktur respons API tidak sesuai
                    $this->logoutUser();
                }
            } else {
                // Jika refresh gagal (misalnya 401 Unauthorized)
                $this->logoutUser();
            }
        } catch (\Exception $e) {
            // Tangani error jaringan atau exception lainnya
            $this->logoutUser();
        }
    }

    private function logoutUser()
    {
        Session::flush();
        // Redirect ke halaman login dengan pesan error
        redirect()->route('login')->with('error', 'Sesi Anda telah kedaluwarsa atau gagal menyegarkan token. Silakan login kembali.')->send();
    }
}
