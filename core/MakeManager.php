<?php

namespace Core;

use Core\Contracts\Make;
use RuntimeException;

class MakeManager implements Make
{
    public function handle(string $makeable, string $name): void
    {
        if (class_exists($class = "\Core\Makeables\Make$makeable")) {
            (new $class)->make($name);

            return;
        }

        throw new RuntimeException("Undefined \Core\Makeables\Make$makeable class.");
    }
}