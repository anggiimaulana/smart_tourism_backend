<?php

return [
    'wilayah' => ['Cirebon', 'Indramayu', 'Majalengka', 'Kuningan'],

    'fastapi' => [
        'base_url'   => env('FASTAPI_BASE_URL', 'http://localhost:8001'),
        'timeout'    => env('FASTAPI_TIMEOUT', 150),
        'secret_key' => env('FASTAPI_SECRET_KEY'),
    ],

    'rate_limits' => [
        'api_global'  => '120,1',   // 120 req/menit global
        'ai_endpoints' => '20,1',    // 20 req/menit untuk AI
        'auth'        => '10,1',    // 10 req/menit untuk auth
    ],

    'cache_ttl' => [
        'wisata_list'      => 3600,    // 1 jam
        'wisata_detail'    => 7200,    // 2 jam
        'kuliner_list'     => 3600,
        'kuliner_detail'   => 7200,
        'nongkrong_list'   => 3600,
        'nongkrong_detail' => 7200,
        'sentiment'        => 86400,   // 24 jam
    ],

    'pagination' => [
        'default' => 12,
        'max'     => 50,
    ],
];
