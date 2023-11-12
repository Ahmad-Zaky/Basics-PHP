<?php

namespace Core\Contracts;

interface Make
{
    public function handle(string $makeable, string $name): void;
}