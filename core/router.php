<?php

$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

$routes = [
    "/" => "home",
    "/contact" => "contact",
    "/about" => "about",
];

route($routes, $uri);