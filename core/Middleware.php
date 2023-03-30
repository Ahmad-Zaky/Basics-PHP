<?php

namespace Core;

use Exception;

class Middleware
{
    public static function resolveDefault()
    {
        foreach (config("middleware.default") as $middleware) {
            (new $middleware)->handle();
        }
    }
     
    public static function resolve($key)
    {
        if (! $key) return;

        if (! $middleware = (config("middleware.custom")[$key] ?? NULL)) {
            throw new Exception("'{$key}' middleware not found !");
        }

        (new $middleware)->handle();
    }
}
