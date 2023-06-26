<?php

namespace Core\Contracts;

interface Cookie
{
    public function set(string $key, mixed $value, int $expire = 0, array $options = []): bool;
    
    public function get(string $key): string;
    
    public function getAll(): array;

    public function expire(string $key): bool;
}