<?php

namespace Core;

use Core\Middlewares\Middleware;
use Core\Response;
use Exception;

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

        foreach ($route["middlewares"] as $middleware ) {
            Middleware::resolve($middleware);
        }

        if (is_callable($route["controller"])) {
            ($route["controller"])(); exit();
        }

        $controller = new $route["controller"][0];
        $action = $route["controller"][1] ?? NULL;
        $action ? $controller->$action() : $controller();
    }

    public static function getRoute($name)
    {
        foreach (self::$routes as $route) {
            if ($route["name"] === $name) {
                return self::getRouteUri($route["uri"]);
            }
        }

        return NULL;
    }

    protected static function register($uri, $controller, $method, $middlewares = [], $name = NULL)
    {
        $uri = self::getRouteUri($uri);

        self::$routes["{$uri}.{$method}"] = [
            "uri" => $uri,
            "controller" => $controller,
            "method" => $method,
            "middlewares" => $middlewares,
            "name" => $name
        ];

        return new self;
    }

    public function only($middleware) 
    {
        self::$routes[array_key_last(self::$routes)]["middlewares"] = is_array($middleware) ? $middleware : [$middleware];

        return $this;
    }

    public function name($name) 
    {
        foreach (self::$routes as $route) {
            if ($route["name"] === $name) {
                throw new Exception("'{$name}' route name already uesed by another route !");
            }
        }

        self::$routes[array_key_last(self::$routes)]["name"] = $name;

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

    public static function getRouteUri($uri = "") 
    {
        return  "/". ltrim(str_replace("//", "/", $uri), "/");
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
