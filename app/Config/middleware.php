<?php

use App\Middlewares\Auth;
use App\Middlewares\Guest;
use App\Middlewares\VerifyCsrfToken;

return [
    "default" => [
        'csrf' => VerifyCsrfToken::class,    
    ],
    "custom" => [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ],
];