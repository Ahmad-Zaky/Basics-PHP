<?php

namespace Core\Contracts;

interface Config
{
    public function get(string $key): mixed;
}