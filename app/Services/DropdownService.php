<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DropdownService
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
        $this->apiToken = session('access_token');
    }

    public function get(string $type): array
    {
        $response = Http::withToken($this->apiToken)
            ->get($this->apiUrl . 'dropdown', [
                'type' => $type
            ]);

        if (!$response->successful()) {
            throw new \Exception(
                $response->json()['message'] ?? 'Gagal mengambil data dropdown'
            );
        }

        return $response->json()['data'] ?? [];
    }
}
