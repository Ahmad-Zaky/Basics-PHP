<?php

session_start();

const BASE_PATH = __DIR__ . DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR;

require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."globals.php";

require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."functions.php";

require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."autoloader.php";

require appPath("routes.php");

require corePath("bootstrap.php");
