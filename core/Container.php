<?php

namespace Core;

use Exception;

class Container
{
    protected $bindings = [];

    public function bind($key, $resolver) 
    {
        $this->bindings[$key] = $resolver;
    }

    public function bindList($binds) 
    {
        array_map(
            fn ($bind, $resolver) => $this->bindings[$bind] = $resolver,
            array_keys($binds),
            $binds
        );
    }

    public function resolve($key)
    {
        if (! array_key_exists($key, $this->bindings)) {
            throw new Exception("No matching binding found for your {$key} !");
        }

        $resolver = $this->bindings[$key];

        return call_user_func($resolver);
    }
}