<?php

return [
    // production
    // 'base_url' => env('API_BASE_URL', 'https://apistikesdh.alvion.id/api/v1/'),
    // 'storage_url' => env('API_STORAGE_URL', 'https://apistikesdh.alvion.id/storage/'),

    // develop
    'base_url' => env('API_BASE_URL', 'http://localhost:8001/api/v1/'),
    'storage_url' => env('API_STORAGE_URL', 'http://localhost:8001/storage/'),
    'keuangan_url' => env('API_URL_KEUANGAN', 'http://localhost:8001'),
    'pmb_url' => env('API_URL_PMB', 'http://localhost:8000/api/pmb'),
];
