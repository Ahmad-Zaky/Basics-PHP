<?php

namespace Core;

use Exception;

use Core\Contracts\{Application, Response};
use Core\Facades\{Route, Translation};

use Core\Exceptions\{
    ForbiddenException,
    ModelNotFoundException,
    RouteNotFoundException
};

class App implements Application
{
    public static string $ROOT_DIR;

    public static self $app;

    protected static $container;

    function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
    }

    public function boot(): void
    {
        $container = new Container;

        App::setContainer($container);
        
        $this->singletonList((new ConfigManager)->get('app.facades'));

        $this->singletonList((new ConfigManager)->get('app.contracts'));
    }

    public function run(): void
    {
        try {
            Route::route();
        } catch (ForbiddenException $e) {
            abort(Response::HTTP_FORBIDDEN, $e->getMessage());
        } catch (ModelNotFoundException $e) {
            abort(Response::HTTP_NOT_FOUND, $e->getMessage());
        } catch (RouteNotFoundException $e) {
            abort(Response::HTTP_NOT_FOUND, $e->getMessage());
        } catch (Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public static function setContainer($container): void
    {
        static::$container = $container;
    }

    public static function container(): Container
    {
        return static::$container;
    }

    public function bind(string $key, mixed $resolver): mixed
    {
        return static::container()->bind($key, $resolver);
    }

    public function bindList(array $binds): mixed
    {
        return static::container()->bindList($binds);
    }

    public function singleton(string $key, mixed $resolver): mixed
    {
        return static::container()->singleton($key, $resolver);
    }

    public function singletonList(array $binds): mixed
    {
        return static::container()->singletonList($binds);
    }

    public static function resolve(string $key): mixed
    {
        return static::container()->resolve($key);
    }

    public function setLocal(?string $local): void
    {
        Translation::setLocal($local);
    }

    public function getLocal(): string
    {
        return Translation::getLocal();
    }
}