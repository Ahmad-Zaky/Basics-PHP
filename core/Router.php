<?php

class Router
{
    protected static $uri = "/";
    
    protected static $routes = [];

    public static function route()
    {
        if (array_key_exists(self::getUri(), self::getRoutes())) {
            require controllersPath(
                str_replace(
                    ".",
                    DIRECTORY_SEPARATOR,
                    self::getRoutes()[self::getUri()]
                ) .".php"
            );
        } else abort(Response::HTTP_NOT_FOUND);
    }

    public static function getUri() 
    {
        return self::$uri = parse_url($_SERVER["REQUEST_URI"])["path"];
    }

    public static function getRoutes() 
    {
        return self::$routes = require appPath("routes.php");
    }
}

Router::route();