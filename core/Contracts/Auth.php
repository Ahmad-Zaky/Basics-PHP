<?php

namespace Core\Contracts;

interface Auth
{
    public function user(): mixed;

    public function attempt(array $credentials): bool;
}