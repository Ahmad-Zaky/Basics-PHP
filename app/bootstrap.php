<?php

$app = new Core\App($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));

$app->boot();

return $app;
