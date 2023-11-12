<?php

namespace Core\Contracts;

use Core\CommandColors;

interface LogContractor
{
    public function print(string $message, string $color = CommandColors::GREEN_COLOR, bool $withDate = true): void;
}