<?php

use App\Middlewares\Auth;
use App\Middlewares\Guest;
use App\Middlewares\Translation;
use App\Middlewares\VerifyCsrfToken;

return [
    "default" => [
        'csrf' => VerifyCsrfToken::class,   
        'trans' => Translation::class,   
    ],
    "custom" => [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ],
];