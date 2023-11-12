<?php

namespace Core;

use Core\Contracts\LogContractor;

class LogManager implements LogContractor
{
    public function print(string $message, string $color = CommandColors::GREEN_COLOR, bool $withDate = true): void
    {
        $date = $withDate ? "[". date("Y-m-d H:i:s") ."] " : "";

        echo PHP_EOL . $color . $date . $message . PHP_EOL;
    }
}