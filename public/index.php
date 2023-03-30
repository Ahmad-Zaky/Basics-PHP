<?php

session_start(); // TODO: Move it to session class 

define('ZAKY_START', microtime(true));

require dirname(__DIR__) .'/vendor/autoload.php';

// TODO: remove when done refactoring
showErrors();

// TODO: remove it when classes are used
require dirname(__DIR__) .'/core/globals.php';

// TODO: move to Router class when handled properly
require dirname(__DIR__) .'/app/routes.php';

$app = require dirname(__DIR__) .'/app/bootstrap.php';

$app->run();


