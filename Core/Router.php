<?php

namespace Core;

use Core\Response;

class Router
{
    protected static $uri = "/";
    
    protected static $routes = [];

    public static function GET($uri, $controller)
    {
        self::register($uri, $controller, __FUNCTION__);
    }

    public static function POST($uri, $controller)
    {
        self::register($uri, $controller, __FUNCTION__);
    }


    public static function PUT($uri, $controller)
    {
        self::register($uri, $controller, __FUNCTION__);
    }

    public static function PATCH($uri, $controller)
    {
        self::register($uri, $controller, __FUNCTION__);
    }

    public static function DELETE($uri, $controller)
    {
        self::register($uri, $controller, __FUNCTION__);
    }

    public static function route()
    {
        if ($route = self::find()) {
            require controllersPath(
                str_replace(
                    ".",
                    DIRECTORY_SEPARATOR,
                    $route["controller"]
                ) .".php"
            );
        } else abort(Response::HTTP_NOT_FOUND);
    }

    protected static function register($uri, $controller, $method)
    {
        self::$routes["{$uri}.{$method}"] = compact("uri", "controller", "method");
    }

    protected static function find() 
    {
        $found = array_key_exists(self::getUri() .".". self::getMethod(), self::getRoutes());

        return $found ? self::getRoutes()[self::getUri() .".". self::getMethod()] : NULL; 
    }

    public static function getUri() 
    {
        return self::$uri = parse_url($_SERVER["REQUEST_URI"])["path"];
    }

    public static function getMethod() 
    {        
        $method = request("_method") ?? $_SERVER["REQUEST_METHOD"];
        
        return strtoupper($method);
    }

    public static function getRoutes() 
    {
        return self::$routes;
    }
}
