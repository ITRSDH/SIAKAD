<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DropdownService
{
    protected string $apiUrl;

    protected ?string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function get(string $type): array
    {
        $requestedTypes = array_map('trim', explode(',', $type));
        $defaultResult = [];
        foreach ($requestedTypes as $t) {
            $defaultResult[$t] = [];
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->get($this->apiUrl.'dropdown', [
                    'type' => $type,
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                return array_merge($defaultResult, $data);
            }

            Log::warning('Dropdown request unsuccessful: '.$type, [
                'status' => $response->status(),
                'message' => $response->json()['message'] ?? null,
            ]);

            // Fallback khusus prodi jika dropdown gagal (misal pembatasan role)
            if (in_array('prodi', $requestedTypes, true)) {
                $landingResponse = Http::withToken($this->apiToken)->get($this->apiUrl.'landing/prodi');
                if ($landingResponse->successful()) {
                    $prodiList = $landingResponse->json('data', []);
                    $defaultResult['prodi'] = collect($prodiList)->map(function ($item) {
                        return [
                            'id' => $item['id'] ?? null,
                            'kode_prodi' => $item['kode_prodi'] ?? null,
                            'prodi' => '('.($item['jenjang_pendidikan'] ?? '').') '.($item['nama_prodi'] ?? ''),
                        ];
                    })->all();
                }
            }

            return $defaultResult;
        } catch (\Throwable $e) {
            Log::error('Dropdown request exception: '.$e->getMessage(), ['type' => $type]);

            return $defaultResult;
        }
    }
}
