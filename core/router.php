<?php

$routes = require "core". DIRECTORY_SEPARATOR ."routes.php";

$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

route($routes, $uri);