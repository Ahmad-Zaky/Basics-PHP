<?php

namespace Core\Contracts;

use Closure;

interface Event
{
    public static function trigger(string $event, array $args): void;

    public static function bind(string $event, Closure $func): void;
}