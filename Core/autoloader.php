<?php

require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."globals.php";

require BASE_PATH . "Core". DIRECTORY_SEPARATOR ."functions.php";

autoload();

require appPath("routes.php");

require corePath("bootstrap.php");
