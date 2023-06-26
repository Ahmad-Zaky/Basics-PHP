<?php

return [
    "name" => env("APP_NAME", "MVC"),
    
    "environtment" => env("APP_ENV", "local"),
    
    "debug" => env("APP_DEBUG", true), 

    "url" => env("APP_URL", "http://localhost:5000"),

    'authenticatable' => \App\Models\User::class,
];
