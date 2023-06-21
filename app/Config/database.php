<?php

return [
    "connection" => [
        "host" => env("DB_HOST", "localhost"),
        "port" => env("DB_PORT", "3306"),
        "dbname" => env("DB_DATABASE", "mvc"),
        "user" => env("DB_USERNAME", "root"),
        "password" => env("DB_PASSWORD", ""),
        "charset" => env("DB_CHARSET", "utf8mb4"),
    ]
];