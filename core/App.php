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
use RuntimeException;

class App implements Application
{
    public static string $ROOT_DIR;

    public static self $app;

    protected static Container $container;

    protected string $namespace;

    /**
     * The framework version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->namespace = "App";
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

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace(): string
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }

    public function getPath(): string
    {
        return self::$ROOT_DIR.DIRECTORY_SEPARATOR.'app';
    }
}