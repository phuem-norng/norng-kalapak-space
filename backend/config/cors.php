<?php

$origins = [];
$frontend = env('FRONTEND_URL', 'http://localhost:3000');
if (is_string($frontend) && $frontend !== '') {
    $origins[] = rtrim($frontend, '/');
}
$extra = env('CORS_ALLOWED_ORIGINS');
if (is_string($extra) && $extra !== '') {
    foreach (explode(',', $extra) as $origin) {
        $trimmed = rtrim(trim($origin), '/');
        if ($trimmed !== '') {
            $origins[] = $trimmed;
        }
    }
}
$origins = array_values(array_unique($origins));
if ($origins === []) {
    $origins = ['http://localhost:3000'];
}

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => $origins,
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
