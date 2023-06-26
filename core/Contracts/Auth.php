<?php

namespace Core\Contracts;

interface Auth
{
    public function user(): mixed;
}