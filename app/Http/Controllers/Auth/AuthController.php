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

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
       
        $response = Http::post($this->apiUrl . 'auth/login', [
            'username' => $request->email,
            'password' => $request->password,
        ]);


        if ($response->successful()) {
            $data = $response->json();

            // Pastikan struktur data API sesuai
            if (isset($data['success']) && $data['success'] === true && isset($data['data'])) {
                $tokenData = $data['data'];

                session([
                    'access_token' => $tokenData['access_token'],
                    'refresh_token' => $tokenData['refresh_token'],
                    'expires_at' => time() + $tokenData['expires_in'],
                    // 'user' => $tokenData['user'],
                ]);

                return redirect()->intended('/');
            } else {
                return back()->withErrors(['email' => 'Login failed: Invalid response from server.']);
            }
        } else {
            $error = $response->json()['error'] ?? 'Login failed';

            return back()->withErrors(['email' => $error]);
        }
    }

    // Dashboard
    public function dashboard()
    {
        $token = session('access_token');

        // Jika belum login, arahkan ke halaman login
        if (!$token) {
            return redirect()->route('login');
        }

        // Cek apakah data user sudah ada di session
        if (session()->has('user')) {
            $user = session('user');
        } else {
            // Jika belum, panggil endpoint auth/me
            $response = Http::withToken($token)->get($this->apiUrl . 'auth/me');

            if ($response->successful()) {
                $user = $response->json();

                // Simpan data user ke session
                session(['user' => $user['user']]);
            } else {
                // Jika gagal (misal token invalid), hapus session dan arahkan ke login
                Session::flush();
                return redirect()->route('login')->with('error', 'Gagal mengambil data pengguna.');
            }
        }

        return view('admin.dashboard.index', compact('user'));
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
