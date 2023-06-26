<?php

namespace Core;

use Exception;

use Core\Contracts\{
    Auth,
    Config,
    Cookie,
    DB,
    Event,
    Middleware,
    Response,
    Migration,
    Request,
    Session
};

use Core\Exceptions\{
    ForbiddenException,
    ModelNotFoundException,
    RouteNotFoundException
};

class App
{
    public static string $ROOT_DIR;

    public static self $app;

    protected static $container;

    function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
    }

    public function boot()
    {
        $container = new Container;

        App::setContainer($container);

        $this->singletonList([
            Config::class => fn() => new ConfigManager,
            DB::class => fn() => DatabaseManager::getInstance(config("database.connection")),
            Session::class => fn() => new SessionManager,
            Cookie::class => fn() => new CookieManager,
            Auth::class => fn() => new AuthenticationManager,
            Router::class => fn() => new Router,
            Request::class => fn() => new RequestManager,
            Validator::class => fn() => new Validator,
            Middleware::class => fn() => new MiddlewareManager,
            Controller::class => fn() => new Controller,
            Model::class => fn() => new Model,
            View::class => fn() => new View,
            Response::class => fn() => new ResponseManager,
            Migration::class => fn() => new MigrationManager,
            Event::class => fn() => new EventManager,
        ]);
    }

    public function run()
    {
        try {
            Router::route();
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

    public static function setContainer($container) 
    {
        static::$container = $container;
    }

    public static function container()
    {
        return static::$container;
    }

    public function bind($key, $resolver)
    {
        return static::container()->bind($key, $resolver);
    }

    public function bindList($binds)
    {
        return static::container()->bindList($binds);
    }

    public function singleton($key, $resolver)
    {
        return static::container()->singleton($key, $resolver);
    }

    public function singletonList($binds)
    {
        return static::container()->singletonList($binds);
    }

    public static function resolve($key)
    {
        return static::container()->resolve($key);
    }
}