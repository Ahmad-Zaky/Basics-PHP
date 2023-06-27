<?php

namespace Core;

use Exception;
use Core\Contracts\{Request, Middleware};
use Core\Exceptions\RouteNotFoundException;
use Core\Contracts\Router;

class RouterManage implements Router
{
    protected static string $uri = "/";
    
    protected static array $uriParams = [];

    protected static array $routes = [];

    protected static string $pattern = "#^%s$#siD";

    protected static string $paramPattern = "[a-zA-Z0-9]*";

    protected static string $wildcard = '/\{(.*?)\}/';

    public static function GET(string $uri, array $controller): self
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function POST(string $uri, array $controller): self
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function PUT(string $uri, array $controller): self
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function PATCH(string $uri, array $controller): self
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    public static function DELETE(string $uri, array $controller): self
    {
        return self::register($uri, $controller, __FUNCTION__);
    }

    protected static function register(string $uri, array $controller, string $method, array $middlewares = [], ?string $name = NULL): self
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

    public function route(): void
    {
        self::registerProviders();

        self::loadRoutes();

        if (! $route = self::find()) {
            throw new RouteNotFoundException;
        }

        app(Middleware::class)->resolveDefault();

        foreach ($route["middlewares"] as $middleware ) {
            app(Middleware::class)->resolve($middleware);
        }

        self::execute($route);
    }

    public static function loadRoutes(): void
    {
        require_once appPath('routes.php');
    }

    public static function registerProviders(): void
    {
        $providers = scandir(providersPath());
        foreach ($providers as $provider) {
            if (in_array($provider, [".", ".."])) continue;

            require providersPath() . $provider;

            $file = pathinfo($provider, PATHINFO_FILENAME);
            $class = '\App\Providers\\'.$file;
            
            (new $class)->register();
        }
    }

    public static function execute(array $route): void
    {
        if (is_callable($route["controller"])) {
            ($route["controller"])(); exit;
        }

        $parameters = self::uriParameters($route["uri"]);
        
        self::addUriPararmsToRequest($parameters);

        $controller = new $route["controller"][0];
        $action = $route["controller"][1] ?? NULL;
        
        $params = hasParameterByType($route["controller"][0], $action, Request::class)
            ? [app(Request::class), ...array_values($parameters)]
            : array_values($parameters);

        $action 
            ? $controller->{$action}(...$params)
            : $controller(...array_values($parameters));
        
        exit;
    }

    protected static function addUriPararmsToRequest(array $params = []): void
    {
        foreach ($params as $key => $value) {
            self::setUriParameter($key, $value); 
        }
    }

    protected static function setUriParameter(string $key, mixed $value): void
    {
        self::$uriParams[$key] = $value;
    }

    protected function param(string $key): mixed
    {
        return self::$uriParams[$key] ?? NULL;
    }

    protected static function uriParameters(string $routeUri): mixed
    {
        $routeUri = trim($routeUri, '/');
        $uri = trim(self::getUri(), '/');
        $uriParts = explode("/", $uri);
        $routeUriParts = explode("/", $routeUri);

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

    protected static function routeParameters(string $uri): array
    {
        preg_match_all(self::$wildcard, $uri, $matches);

        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }

    protected static function handleParams(string $uri): string
    {
        return preg_replace(self::$wildcard, self::$paramPattern, $uri);
    }

    public static function getRoute(string $name, array $params = []): ?string
    {
        foreach (self::$routes as $route) {
            if ($route["name"] === $name) {
                self::validateParams(self::routeParameters($route["uri"]), $params);

                $boundUri = self::bindParams(self::getRouteUri($route["uri"]), $params);
                $queryString = self::toQueryString($params, self::routeParameters($route["uri"]));

                return  $boundUri . $queryString;
            }
        }

        return NULL;
    }

    public function middleware(mixed $middleware): self
    {
        self::$routes[array_key_last(self::$routes)]["middlewares"] = is_array($middleware) ? $middleware : [$middleware];

        return $this;
    }

    public function name(string $name): self
    {
        foreach (self::$routes as $route) {
            if ($route["name"] === $name) {
                throw new Exception("'{$name}' route name already uesed by another route !");
            }
        }

        self::$routes[array_key_last(self::$routes)]["name"] = $name;

        return $this;
    }
    
    protected static function find(): ?array
    {
        foreach (self::$routes as $route) {
            $pattern = sprintf(static::$pattern, self::handleParams($route["uri"]));
            if (! preg_match($pattern, self::getUri()) || $route["method"] !== self::getMethod()) continue;

            return $route;
        }

        return NULL;
    }

    public static function getUri(): ?string
    {
        return self::$uri = parse_url($_SERVER["REQUEST_URI"])["path"];
    }

    public static function bindParams(string $uri, array $params = []): string
    {
        if (empty($params)) return $uri;
        
        $boundUri = $uri;
        foreach ($params as  $key => $value) {
            $boundUri = str_replace("{". $key ."}", $value, $boundUri);
        }

        return $boundUri;
    }

    public static function getRouteUri(string $uri = ""): string
    {
        return  "/". ltrim(str_replace("//", "/", $uri), "/");
    }

    public static function getMethod(): string
    {        
        $method = request("_method") ?? $_SERVER["REQUEST_METHOD"];
        
        return strtoupper($method);
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function validateParams(array $uriParams, array $params): void
    {
        foreach ($uriParams as $uriParam) {
            if (array_key_exists($uriParam, $params)) continue;

            throw new Exception("'{$uriParam}' route parameter is missing !");
        }
    }

    public static function toQueryString(array $params = [], array $except = []): string
    {
        $params = array_filter($params, function($key) use ($except) {
            return !in_array($key, $except);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($params)) {
            return "";
        }

        return '?' . http_build_query(array_diff_key($params, $except));
    }

    public function urlIs(string $value): bool
    {
        return $_SERVER["REQUEST_URI"] === $value;
    }

    public function urlIn(array $routes): bool
    {
        return in_array($_SERVER["REQUEST_URI"], $routes) && true;
    }
}
