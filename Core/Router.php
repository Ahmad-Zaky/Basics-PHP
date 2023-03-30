<?php

namespace Core;

use Core\Middleware;
use Core\Response;
use Exception;

class Router
{
    protected static $uri = "/";
    
    protected static $routes = [];

    protected static $pattern = "#^%s$#siD";

    protected static $paramPattern = "[a-zA-Z0-9]*";

    protected static $wildcard = '/\{(.*?)\}/';

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

    protected static function register($uri, $controller, $method, $middlewares = [], $name = NULL)
    {
        $uri = self::getRouteUri($uri);

        self::$routes[] = [
            "uri" => $uri,
            "controller" => $controller,
            "method" => $method,
            "middlewares" => $middlewares,
            "name" => $name
        ];

        return new self;
    }

    public static function route()
    {
        if (! $route = self::find()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        Middleware::resolveDefault();

        foreach ($route["middlewares"] as $middleware ) {
            Middleware::resolve($middleware);
        }

        self::execute($route);
    }

    public static function execute($route)
    {
        if (is_callable($route["controller"])) {
            ($route["controller"])(); exit;
        }

        $parameters = self::uriParameters($route["uri"]);
        
        self::addUriPararmsToRequest($parameters);

        $controller = new $route["controller"][0];
        $action = $route["controller"][1] ?? NULL;

        $action 
            ? $controller->{$action}(...array_values($parameters))
            : $controller(...array_values($parameters));
        
        exit;
    }

    protected static function addUriPararmsToRequest($params = [])
    {
        setRequest($params);
    }

    protected static function uriParameters($routeUri)
    {
        $routeUri = trim($routeUri, '/');
        $uri = trim(self::getUri(), '/');
        $uriParts = explode("/", $uri);
        $routeUriParts = explode("/", $routeUri);

        // dd(explode("/", $routeUri));

        if (! empty($routeUriParts) && count($routeUriParts) !== count($uriParts)) {
            throw new Exception("Failed to fetch route parameters !");
        }

        $params = [];
        foreach ($routeUriParts as $key => $routeUriPart) {
            if (preg_match(self::$wildcard, $routeUriPart, $match)) {
                $params[$match[1]] = $uriParts[$key];
            }
        }

        return $params;
    }

    protected static function routeParameters($uri)
    {
        preg_match_all(self::$wildcard, $uri, $matches);

        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }

    protected static function handleParams($uri) 
    {
        return preg_replace(self::$wildcard, self::$paramPattern, $uri);
    }

    public static function getRoute($name, $params = [])
    {
        foreach (self::$routes as $route) {
            if ($route["name"] === $name) {

                self::validateParams(self::routeParameters($route["uri"]), $params);

                return self::bindParams(self::getRouteUri($route["uri"]), $params);
            }
        }

        return NULL;
    }

    public function middleware($middleware) 
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
        foreach (self::$routes as $route) {
            $pattern = sprintf(static::$pattern, self::handleParams($route["uri"]));
            if (! preg_match($pattern, self::getUri()) || $route["method"] !== self::getMethod()) continue;

            return $route;
        }

        return NULL;
    }

    public static function getUri() 
    {
        return self::$uri = parse_url($_SERVER["REQUEST_URI"])["path"];
    }

    public static function bindParams($uri, $params = [])
    {
        if (empty($params)) return $uri;
        
        $boundUri = $uri;
        foreach ($params as  $key => $value) {
            $boundUri = str_replace("{". $key ."}", $value, $boundUri);
        }

        return $boundUri;
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

    public static function validateParams($uriParams, $params)
    {
        foreach ($uriParams as $uriParam) {
            if (array_key_exists($uriParam, $params)) continue;

            throw new Exception("'{$uriParam}' route parameter is missing !");
        }
    }
}
