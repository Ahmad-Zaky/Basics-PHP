<?php

namespace Core\Contracts;

interface Middleware
{
    public function resolveDefault(): void;

    public function resolve(string $key): void;
}
