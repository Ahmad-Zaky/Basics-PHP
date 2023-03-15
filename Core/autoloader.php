<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."globals.php";

require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."functions.php";

showErrors();

autoload();

require appPath("routes.php");

require corePath("init.php");
