<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie','login', 'register', 'logout'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')], // frontend URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Set-Cookie'],
    'max_age' => 0,
    'supports_credentials' => true, // ← MUST BE true for cookies
];
