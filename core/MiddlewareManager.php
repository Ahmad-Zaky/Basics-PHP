<?php

namespace Core;

use Core\Contracts\Middleware;
use Exception;

class MiddlewareManager implements Middleware
{
    public function resolveDefault(): void
    {
        foreach (config("middleware.default") as $middleware) {
            (new $middleware)->handle();
        }
    }

    public function resolve($key): void
    {
        if (! $key) return;

        if (! $middleware = (config("middleware.custom")[$key] ?? NULL)) {
            throw new Exception("'{$key}' middleware not found !");
        }

        (new $middleware)->handle();
    }
}
