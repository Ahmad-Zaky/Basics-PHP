<?php

namespace Core;

use Exception;

class Container
{
    protected $bindings = [];

    public function bindings()
    {
        return $this->bindings;
    }

    public function bind($key, $resolver) 
    {
        $this->bindings[$key] = ['singleton' => false, 'resolver' => $resolver];
    }

    public function bindList($binds) 
    {
        array_map(
            fn ($bind, $resolver) => $this->bind($bind, $resolver),
            array_keys($binds),
            $binds
        );
    }

    public function singleton($key, $resolver) 
    {
        $this->bindings[$key] = ['singleton' => true, 'resolver' => call_user_func($resolver)];
    }

    public function singletonList($binds) 
    {
        array_map(
            fn ($bind, $resolver) => $this->singleton($bind, $resolver),
            array_keys($binds),
            $binds
        );
    }

    public function resolve($key)
    {
        if (! array_key_exists($key, $this->bindings)) {
            throw new Exception(__("No matching binding found for your :key !", [
                'key' => $key
            ]));
        }

        $resolver = $this->bindings[$key];

        return $resolver["singleton"] ? $resolver["resolver"] : call_user_func($resolver["resolver"]);
    }
}