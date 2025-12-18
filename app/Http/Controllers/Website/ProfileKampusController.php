<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProfileKampusController extends Controller
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
            $response = Http::withToken($this->apiToken)->get($this->apiUrl . 'profile-kampus');

            if ($response->successful()) {
                $profileKampus = $response->json()['data'] ?? null;

                return view('admin.master.website.profile_kampus.index', compact('profileKampus'));
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
                'judul' => 'sometimes|string|max:255',
                'deskripsi' => 'nullable|string',
                'visi' => 'nullable|string',
                'misi' => 'nullable|string',
                'struktur_image' => 'nullable|file|max:2048',
                'fasilitas' => 'nullable|string',
            ]);

            // Buat data dasar untuk dikirim ke API
            $data = $request->only([
                'judul', 'deskripsi', 'visi', 
                'misi', 'struktur_image', 'fasilitas'
            ]);

            // Siapkan HTTP request
            $httpRequest = Http::withToken($this->apiToken);

            // Jika ada file, gunakan multipart/form-data
            $hasFiles = $request->hasFile('struktur_image');
            
            if ($hasFiles) {
                // Attach files jika ada
                if ($request->hasFile('struktur_image')) {
                    $httpRequest = $httpRequest->attach(
                        'struktur_image', 
                        file_get_contents($request->file('struktur_image')), 
                        $request->file('struktur_image')->getClientOriginalName()
                    );
                }

                // Kirim sebagai multipart
                $response = $httpRequest->post($this->apiUrl . 'profile-kampus', $data);
            } else {
                // Kirim sebagai JSON jika tidak ada file
                $response = Http::withToken($this->apiToken)
                    ->post($this->apiUrl . 'profile-kampus', $data);
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
            $testResponse = Http::withToken($this->apiToken)->get($this->apiUrl . 'profile-kampus');
            
            if (!$testResponse->successful()) {
                Log::error('API Connection Test Failed', [
                    'url' => $this->apiUrl . 'profile-kampus',
                    'status' => $testResponse->status(),
                    'body' => $testResponse->body()
                ]);
                
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Koneksi ke API gagal',
                        'debug' => [
                            'api_url' => $this->apiUrl . 'profile-kampus',
                            'status' => $testResponse->status(),
                            'response' => $testResponse->body()
                        ]
                    ],
                    500,
                );
            }

            return response()->json($testResponse->json());
        } catch (\Exception $e) {
            Log::error('Show Profile Kampus Error', [
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
                'judul' => 'sometimes|string|max:255',
                'deskripsi' => 'nullable|string',
                'visi' => 'nullable|string',
                'misi' => 'nullable|string',
                'struktur_image' => 'nullable|file|max:2048',
                'fasilitas' => 'nullable|string',
            ]);

            // Siapkan data untuk multipart/form-data
            $httpRequest = Http::withToken($this->apiToken);

            // Attach files if present
            if ($request->hasFile('struktur_image')) {
                $httpRequest = $httpRequest->attach(
                    'struktur_image', 
                    file_get_contents($request->file('struktur_image')), 
                    $request->file('struktur_image')->getClientOriginalName()
                );
            }

            // Buat data untuk dikirim ke API
            $data = $request->only([
                'judul', 'deskripsi', 'visi', 
                'misi', 'struktur_image', 'fasilitas'
            ]);

            // Untuk single content, gunakan ID 1
            $response = $httpRequest->post($this->apiUrl . "profile-kampus/1?_method=PUT", $data);

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
            $response = Http::withToken($this->apiToken)->delete($this->apiUrl . "profile-kampus/1");

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
