<?php
return [
    // The final route would look something like this: /auth/{GUID}
    'auth_route' => '/auth',
    'base_url' => env('BYDESIGN_REST_API_BASE_URL'),

    'success_redirect_url' => '/',
    'failed_redirect_url' => '/failed-authentication',

    'username' => env('BYDESIGN_USERNAME', 'api'),
    'password' => env('BYDESIGN_PASSWORD')
];
