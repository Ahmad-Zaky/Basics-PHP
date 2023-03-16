<?php

namespace Core;

use Core\Middlewares\Middleware;
use Core\Response;

class Router
{
    protected static $uri = "/";
    
    protected static $routes = [];

    public static function GET($uri, $controller)
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function POST($uri, $controller)
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function PUT($uri, $controller)
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function PATCH($uri, $controller)
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function DELETE($uri, $controller)
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function route()
    {
        if (! $route = self::find()) {
            abort(Response::HTTP_NOT_FOUND);
        }
        
        Middleware::resolve($route["middleware"]);

        require controllersPath(str_replace(".", DIRECTORY_SEPARATOR, $route["controller"]) .".php");
    }

    protected static function register($uri, $controller, $method, $middleware = NULL)
    {
        self::$routes["{$uri}.{$method}"] = compact("uri", "controller", "method", "middleware");
        
        return new self;
    }

    public function only($middleware) 
    {
        self::$routes[array_key_last(self::$routes)]["middleware"] = $middleware;

        return $this;
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
