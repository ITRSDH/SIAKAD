<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        // Hapus token lama jika ada saat menampilkan login
        Session::forget(['access_token', 'refresh_token', 'expires_at']);

        return view('auth.login');
    }

    // Proses login (Web)
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required', // email / nim / nup
            'password' => 'required',
        ], [
            'username.required' => 'Email / NIM / NUP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Kirim ke API
        $response = Http::post($this->apiUrl . 'auth/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Validasi struktur response API
            if (
                isset($data['success']) &&
                $data['success'] === true &&
                isset($data['data'])
            ) {
                $tokenData = $data['data'];

                // Simpan token ke session
                session([
                    'access_token' => $tokenData['access_token'],
                    'refresh_token' => $tokenData['refresh_token'],
                    'expires_at' => time() + $tokenData['expires_in'],
                    // 'user' => $tokenData['user'], // jika perlu
                ]);

                return redirect()->intended('/');
            }

            return back()->withErrors([
                'username' => 'Login gagal: respon server tidak valid.'
            ]);
        }

        // Jika API return error
        $error = $response->json()['error']
            ?? $response->json()['message']
            ?? 'Login gagal';

        return back()->withErrors([
            'username' => $error
        ]);
    }


    public function profile(Request $request)
    {
        $token = session('access_token');

        // Jika belum login, arahkan ke halaman login
        if (!$token) {
            return redirect()->route('login');
        }

        // --- Bagian 1: Ambil data user (dari session atau API) ---
        if (session()->has('user')) {
            $user = session('user');
        } else {
            $response = Http::withToken($token)->get($this->apiUrl . 'auth/me');

            if ($response->successful()) {
                $apiData = $response->json();

                if ($apiData['success']) {
                    $user = $apiData['user'];
                    // Simpan *hanya* data user ke session, sesuai permintaan
                    session(['user' => $user]);
                } else {
                    Session::flush();
                    return redirect()->route('login')->with('error', 'Gagal mengambil data pengguna dari API.');
                }
            } else {
                Session::flush();
                return redirect()->route('login')->with('error', 'Gagal mengambil data pengguna. (' . $response->status() . ')');
            }
        }

        // --- Bagian 2: Ambil profile_type dan profile (dari API) ---
        $profileResponse = Http::withToken($token)->get($this->apiUrl . 'auth/me');

        if ($profileResponse->successful()) {
            $apiData = $profileResponse->json();

            if ($apiData['success']) {
                $profile = $apiData['profile'];
                $profileType = $apiData['profile_type'];
            } else {
                $profile = null;
                $profileType = null;
            }
        } else {
            Session::flush();
            return redirect()->route('login')->with('error', 'Gagal mengambil data profil. (' . $profileResponse->status() . ')');
        }

        // Kirim data user (dari session), dan profile/profile_type (dari API terbaru) ke view
        $profile_type = $profileType; // Alias untuk compact jika view mengharapkan $profile_type
        return view('profile.index', compact('user', 'profile', 'profile_type'));
    }


    // Logout
    public function logout()
    {
        $token = session('access_token');
        $refreshToken = session('refresh_token');

        if ($refreshToken) {
            try {
                // Kirim refresh token ke API untuk dicabut
                Http::withToken($token)->post($this->apiUrl . 'auth/logout', [
                    'refresh_token' => $refreshToken
                ]);
            } catch (\Exception $e) {
                // Log error jika perlu
                // \Log::error('Logout API failed: ' . $e->getMessage());
            }
        }

        Session::flush();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // Endpoint untuk refresh token secara manual (misalnya via AJAX)
    public function refreshToken(Request $request)
    {
        if (!Session::has('refresh_token')) {
            return response()->json(['success' => false, 'message' => 'Tidak ada refresh token'], 401);
        }

        try {
            $response = Http::post($this->apiUrl . 'auth/refresh', [
                'refresh_token' => session('refresh_token')
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true && isset($data['data'])) {
                    $tokenData = $data['data'];

                    // Update session dengan token baru
                    session([
                        'access_token' => $tokenData['access_token'],
                        'refresh_token' => $tokenData['refresh_token'],
                        'expires_at' => time() + $tokenData['expires_in'],
                    ]);

                    return response()->json([
                        'success' => true,
                        'access_token' => $tokenData['access_token'],
                        'expires_at' => time() + $tokenData['expires_in']
                    ]);
                } else {
                    Session::flush();
                    return response()->json(['success' => false, 'message' => 'API refresh gagal'], 401);
                }
            } else {
                $error = $response->json()['error'] ?? 'API refresh gagal';
                Session::flush();
                return response()->json(['success' => false, 'message' => $error], 401);
            }
        } catch (\Exception $e) {
            Session::flush();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }
}
