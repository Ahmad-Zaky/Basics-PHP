<?php

define('ZAKY_START', microtime(true));

require dirname(__DIR__) .'/vendor/autoload.php';

$app = require dirname(__DIR__) .'/app/bootstrap.php';

$app->run();


