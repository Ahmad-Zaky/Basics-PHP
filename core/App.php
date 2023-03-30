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

        $this->bindList([
            Config::class => fn() => new Config,
            DB::class => fn() => new DB(config("database.connection")),
            Session::class => fn() => new Session,
            Router::class => fn() => new Router,
            Request::class => fn() => new Request,
            Validator::class => fn() => new Validator,
            Middleware::class => fn() => new Middleware,
            Controller::class => fn() => new Controller,
            Model::class => fn() => new Model,
            View::class => fn() => new View,
            Response::class => fn() => new Response,
        ]);
    }

    public function run()
    {
        run();
    }

    public static function setContainer($container) 
    {
        static::$container = $container;
    }

    public static function container()
    {
        return static::$container;
    }

    public static function bind($key, $resolver)
    {
        return static::container()->bind($key, $resolver);
    }

    public static function bindList($binds)
    {
        return static::container()->bindList($binds);
    }

    public static function resolve($key)
    {
        return static::container()->resolve($key);
    }
}