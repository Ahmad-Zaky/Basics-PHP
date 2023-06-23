<?php

namespace Core;

class Cookie
{
    protected $options = [];

    public function set($key, $value, $expire = 0, $options = [])
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

    public function get($key) 
    {
        return $_COOKIE[$key] ?? NULL;
    }

    public function getAll($key) 
    {
        return $_COOKIE;
    }

    public function expire($key) 
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            return $this->set($key, '', -1);   
        }
    }
}