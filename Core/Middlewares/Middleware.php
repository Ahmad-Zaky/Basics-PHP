<?php

namespace Core\Middlewares;

use Exception;

class Middleware
{
    public const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ];
     
    public const DEFAULT = [
        'csrf' => VerifyCsrfToken::class,
    ];
     
    public static function resolveDefault()
    {
        foreach (Middleware::DEFAULT as $middleware) {
            (new $middleware)->handle();
        }
    }
     
    public static function resolve($key)
    {
        if (! $key) return;

        if (! $middleware = (Middleware::MAP[$key] ?? NULL)) {
            throw new Exception("'{$key}' middleware not found !");
        }
        
        (new $middleware)->handle();
    }
}
