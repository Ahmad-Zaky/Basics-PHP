<?php

namespace Core;

use Core\Contracts\Cookie;

class CookieManager implements Cookie
{
    protected $options = [];

    public function set(string $key, mixed $value, int $expire = 0, array $options = []): bool
    {
        if (! empty($options)) {
            $this->options = $options;
        }

        return setcookie(
            $key,
            $value,
            time() + $expire,
            $this->options["path"] ?? "",
            $this->options["domain"] ?? "",
            $this->options["secure"] ?? false,
            $this->options["httponly"] ?? false
        );
    }

    public function get(string $key): string
    {
        return $_COOKIE[$key] ?? NULL;
    }

    public function getAll(): array 
    {
        return $_COOKIE;
    }

    public function expire(string $key): bool
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            return $this->set($key, '', -1);   
        }

        return false;
    }
}