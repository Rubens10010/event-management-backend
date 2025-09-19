<?php

return [

    'paths' => [
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'api/*',
        '*'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [env('FRONTEND_URL'), 'http://localhost:3000', 'https://www.paseconfiable.com'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
