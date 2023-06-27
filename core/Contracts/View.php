<?php

namespace Core\Contracts;

interface View
{
    public function render(string $path = "", array $attributes = []): void;
}
