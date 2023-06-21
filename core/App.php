<?php

namespace Core;

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
            Config::class => fn() => new Config,
            DB::class => fn() => new DB(config("database.connection")),
            Session::class => fn() => new Session,
            Auth::class => fn() => new Auth,
            Router::class => fn() => new Router,
            Request::class => fn() => new Request,
            Validator::class => fn() => new Validator,
            Middleware::class => fn() => new Middleware,
            Controller::class => fn() => new Controller,
            Model::class => fn() => new Model,
            View::class => fn() => new View,
            Response::class => fn() => new Response,
            Migration::class => fn() => new Migration,
        ]);
    }

    public function run()
    {
        Router::route();
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