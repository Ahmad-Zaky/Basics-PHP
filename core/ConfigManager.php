<?php

namespace Core;

use Core\Contracts\Config;

class ConfigManager implements Config
{
    public function get(string $keys = ""): mixed
    {
        $keyParts = explode(".", $keys);

        $file = $keyParts[0]; unset($keyParts[0]);

        $config = require appPath("Config". DIRECTORY_SEPARATOR ."{$file}.php");

        if (! empty($keyParts)) {
            $found = $config;
            foreach ($keyParts as $key) {
                $found = $found[$key] ?? NULL;
    
                if (! $found) return NULL;
            }
    
            return $found;
        }

        return $config;
    }
}
