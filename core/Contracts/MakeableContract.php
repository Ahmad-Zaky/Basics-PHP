<?php

namespace Core\Contracts;

interface MakeableContract
{
    public function make(string $name): void;
}
