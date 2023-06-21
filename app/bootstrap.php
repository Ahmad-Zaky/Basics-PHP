<?php

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new Core\App($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));

$app->boot();

return $app;
