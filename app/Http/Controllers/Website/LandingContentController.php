<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingContentController extends Controller
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
        
        // Debug logging
        Log::info('LandingContentController initialized', [
            'api_url' => $this->apiUrl,
            'token_exists' => !empty($this->apiToken),
            'token_preview' => $this->apiToken ? substr($this->apiToken, 0, 20) . '...' : 'null'
        ]);
    }

    public function index()
    {
        try {
            // Ambil data landing content (single content)
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'landing-content');

            if ($response->successful()) {
                $landingContent = $response->json()['data'] ?? null;

                return view('admin.master.website.landing_content.index', compact('landingContent'));
            }

            return back()->with('error', 'Gagal mengambil data dari API');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input dengan aturan yang lebih spesifik
            $request->validate([
                'hero_title' => 'nullable|string|max:255',
                'hero_subtitle' => 'nullable|string',
                'hero_background' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'jumlah_program_studi' => 'nullable|integer|min:0',
                'jumlah_mahasiswa' => 'nullable|integer|min:0',
                'jumlah_dosen' => 'nullable|integer|min:0',
                'jumlah_mitra' => 'nullable|integer|min:0',
                'keunggulan' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'nama_aplikasi' => 'nullable|string|max:255',
                'deskripsi_footer' => 'nullable|string',
                'facebook' => 'nullable|url',
                'twitter' => 'nullable|url',
                'instagram' => 'nullable|url',
                'linkedin' => 'nullable|url',
                'youtube' => 'nullable|url',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
            ]);

            // Buat data dasar untuk dikirim ke API
            $data = $request->only([
                'hero_title', 'hero_subtitle', 'jumlah_program_studi', 
                'jumlah_mahasiswa', 'jumlah_dosen', 'jumlah_mitra', 
                'keunggulan', 'nama_aplikasi', 'deskripsi_footer', 
                'facebook', 'twitter', 'instagram', 'linkedin', 
                'youtube', 'alamat', 'telepon', 'email'
            ]);

            // Siapkan HTTP request
            $httpRequest = Http::withToken($this->apiToken);

            // Jika ada file, gunakan multipart/form-data
            $hasFiles = $request->hasFile('hero_background') || $request->hasFile('logo');
            
            if ($hasFiles) {
                // Attach files jika ada
                if ($request->hasFile('hero_background')) {
                    $httpRequest = $httpRequest->attach(
                        'hero_background', 
                        file_get_contents($request->file('hero_background')), 
                        $request->file('hero_background')->getClientOriginalName()
                    );
                }

                if ($request->hasFile('logo')) {
                    $httpRequest = $httpRequest->attach(
                        'logo', 
                        file_get_contents($request->file('logo')), 
                        $request->file('logo')->getClientOriginalName()
                    );
                }

                // Kirim sebagai multipart
                $response = $httpRequest->post($this->apiUrl . 'landing-content', $data);
            } else {
                // Kirim sebagai JSON jika tidak ada file
                $response = Http::withToken($this->apiToken)
                    ->post($this->apiUrl . 'landing-content', $data);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // Log error untuk debugging
            Log::error('API Error Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'data_sent' => $data
            ]);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke API: ' . ($response->json()['message'] ?? 'Unknown error'),
                    'errors' => $response->json()['errors'] ?? [],
                    'debug' => [
                        'status_code' => $response->status(),
                        'response_body' => $response->json()
                    ]
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
            Log::error('Landing Content Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'debug' => 'Check logs for more details'
                ],
                500,
            );
        }
    }

    public function show($id = null)
    {
        try {
            // Test koneksi API terlebih dahulu
            $testResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'landing-content');
            
            if (!$testResponse->successful()) {
                Log::error('API Connection Test Failed', [
                    'url' => $this->apiUrl . 'landing-content',
                    'status' => $testResponse->status(),
                    'body' => $testResponse->body()
                ]);
                
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Koneksi ke API gagal',
                        'debug' => [
                            'api_url' => $this->apiUrl . 'landing-content',
                            'status' => $testResponse->status(),
                            'response' => $testResponse->body()
                        ]
                    ],
                    500,
                );
            }

            return response()->json($testResponse->json());
        } catch (\Exception $e) {
            Log::error('Show Landing Content Error', [
                'message' => $e->getMessage(),
                'api_url' => $this->apiUrl,
                'token_exists' => !empty($this->apiToken)
            ]);
            
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'debug' => [
                        'api_url' => $this->apiUrl,
                        'token_exists' => !empty($this->apiToken)
                    ]
                ],
                500,
            );
        }
    }

    public function update(Request $request, $id = null)
    {
        try {
            // Validasi input
            $request->validate([
                'hero_title' => 'nullable|string|max:255',
                'hero_subtitle' => 'nullable|string',
                'hero_background' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
                'jumlah_program_studi' => 'nullable|integer',
                'jumlah_mahasiswa' => 'nullable|integer',
                'jumlah_dosen' => 'nullable|integer',
                'jumlah_mitra' => 'nullable|integer',
                'keunggulan' => 'nullable|string',
                'logo' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
                'nama_aplikasi' => 'nullable|string',
                'deskripsi_footer' => 'nullable|string',
                'facebook' => 'nullable|string',
                'twitter' => 'nullable|string',
                'instagram' => 'nullable|string',
                'linkedin' => 'nullable|string',
                'youtube' => 'nullable|string',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string',
                'email' => 'nullable|string|email',
            ]);

            // Siapkan data untuk multipart/form-data
            $httpRequest = Http::withToken($this->apiToken);

            // Attach files if present
            if ($request->hasFile('hero_background')) {
                $httpRequest = $httpRequest->attach(
                    'hero_background', 
                    file_get_contents($request->file('hero_background')), 
                    $request->file('hero_background')->getClientOriginalName()
                );
            }

            if ($request->hasFile('logo')) {
                $httpRequest = $httpRequest->attach(
                    'logo', 
                    file_get_contents($request->file('logo')), 
                    $request->file('logo')->getClientOriginalName()
                );
            }

            // Buat data untuk dikirim ke API
            $data = $request->only([
                'hero_title', 'hero_subtitle', 'jumlah_program_studi', 
                'jumlah_mahasiswa', 'jumlah_dosen', 'jumlah_mitra', 
                'keunggulan', 'nama_aplikasi', 'deskripsi_footer', 
                'facebook', 'twitter', 'instagram', 'linkedin', 
                'youtube', 'alamat', 'telepon', 'email'
            ]);

            // Untuk single content, gunakan ID 1
            $response = $httpRequest->post($this->apiUrl . "landing-content/1?_method=PUT", $data);

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

    public function destroy($id = null)
    {
        try {
            // Untuk single content, gunakan ID 1
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "landing-content/1");

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
