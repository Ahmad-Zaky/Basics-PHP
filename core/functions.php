<?php

use Core\{
    App,
    Session,
    Router,
    View,
    Validator
};

use Core\Contracts\{
    Cookie,
    Config,
    Auth,
    Response,
    Request,
    Migration,
    Event
};

use Core\Exceptions\ForbiddenException;

/**
 * env function
 *
 * @return void
 */
if (! function_exists("env")) {
    function env(string $key = "", string|NULL $default = NULL): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}

/**
 * config function
 *
 * @return void
 */
if (! function_exists("config")) {
    function config(string $keys = "", string|NULL $default = NULL): mixed
    {
        return app(Config::class)->get($keys) ?? $default;
    }
}

/**
 * session function
 *
 * @return void
 */
if (! function_exists("migrate")) {
    function migrate(): void
    {
        app(Migration::class)->migrate();
    }
}

/**
 * signin function
 *
 * @return void
 */
if (! function_exists("signin")) {
    function signin(mixed $user = NULL): void
    {
        session()->signin($user);
    }
}

/**
 * signout function
 *
 * @return void
 */
if (! function_exists("signout")) {
    function signout(): void
    {
        session()->destroy();
    }
}

/**
 * auth function
 *
 * @return void
 */
if (! function_exists("auth")) {
    function auth(string $key = ""): mixed
    {
        $auth = app(Auth::class)->user();
        
        if ($key) {
            return $auth && $auth->has($key) ? $auth->{$key} : NULL;
        }

        return $auth;
    }
}

/**
 * auth function
 *
 * @return void
 */
if (! function_exists("guest")) {
    function guest(): bool
    {
        return ! auth();
    }
}

/**
 * request function
 *
 * @return void
 */
if (! function_exists("request")) {
    function request(string $key = ""): mixed
    {
        $request = app(Request::class);

        if ($key) {
            return $request->body()[$key] ?? NULL;
        }

        return $request;
    }
}

/**
 * event function
 *
 * @return void
 */
if (! function_exists("event")) {
    function event(): Event
    {
        return app(Event::class);
    }
}

/**
 * response function
 *
 * @return void
 */
if (! function_exists("response")) {
    function response(): Response
    {
        return app(Response::class);
    }
}

/**
 * cookie function
 *
 * @return void
 */
if (! function_exists("cookie")) {
    function cookie(string $key = ""): Cookie|NULL
    {
        $cookie = app(Cookie::class);

        if ($key) {
            return $cookie->get($key) ?? NULL;
        }

        return $cookie;
    }
}

/**
 * csrfToken function
 *
 * @return string
 */
if (! function_exists("csrfToken")) {
    function csrfToken(): string
    {
        return session()->csrf();
    }
}

/**
 * generateToken function
 *
 * @return string
 */
if (! function_exists("generateToken")) {
    function generateToken(): string
    {
        return session()->genCsrf();
    }
}

/**
 * csrfInput function
 *
 * @return string
 */
if (! function_exists('csrfInput')) {
    function csrfInput(): string
    {
        return session()->csrfInput();
    }
}

/**
 * requestMethod function
 *
 * @return string
 */
if (! function_exists('requestMethod')) {
    function requestMethod(): string
    {    
        return request()->method();
    }
}

/**
 * distroyCsrfToken function
 *
 * @return void
 */
if (! function_exists("distroyCsrfToken")) {
    function distroyCsrfToken(): void
    {
        session()->destroyCsrf();
    }
}

/**
 * session function
 *
 * @return void
 */
if (! function_exists("session")) {
    function session(string $key = ""): mixed
    {
        $session = app(Session::class);
        if ($key) {
            return $session->get($key);
        }

        return $session;
    }
}

/**
 * app function
 *
 * @return void
 */
if (! function_exists("app")) {
    function app(string $key = ""): mixed
    {
        if ($key) {
            return App::resolve($key);
        }

        return App::$app;
    }
}

/**
 * old function
 *
 * @return void
 */
if (! function_exists("old")) {
    function old(string $key, mixed $default = NULL): string
    {
        return session()->getFlash("old")[$key] ?? $default;
    }
}

/**
 * showErrors function
 *
 * @return void
 */
if (! function_exists("showErrors")) {
    function showErrors(): void
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

/**
 * d function
 *
 * @param [type] $dump
 * @return void
 */
if (! function_exists('d')) {
	function d(string $dump=''): string
	{
		return is_string($dump) AND die($dump);
	}
}

/**
 * dd function
 *
 * @param [type] $dump
 * @return void
 */
if (! function_exists('dd')) {
    function dd(): void
    {
        foreach (func_get_args() as $dump) {
            echo "<pre>";
            var_dump($dump);
            echo "</pre>";
        }

        die;
    }
}

/**
 * dump function
 *
 * @param [type] $dump
 * @return void
 */
if (! function_exists('dump')) {
    function dump(string $dump=''): void
    {
        foreach (func_get_args() as $dump) {
            echo "<pre>";
            var_dump($dump);
            echo "</pre>";
        }
    }
}   

/**
 * urlIs function
 *
 * @param [type] $value
 * @return void
 */
if (! function_exists('urlIs')) {
    function urlIs(string $value = ''): bool
    {
        return app(Router::class)->urlIs($value);
    }
}

/**
 * urlIn function
 *
 * @param [type] $value
 * @return bool
 */
if (! function_exists('urlIn')) {
    function urlIn(array $routes): bool
    {
        return app(Router::class)->urlIn($routes);
    }
}

/**
 * abort function
 *
 * @param [type] $code
 * @return void
 */
if (! function_exists('abort')) {
    function abort(int $code = 404, string|NULL $message = NULL): void
    {
        app(Response::class)->abort($code, $message);
    }
}

/**
 * view function
 *
 * @return string
 */
if (! function_exists('view')) {
    function view(string $path = "", array $attributes = []): void
    {
        app(View::class)->render($path, $attributes);
    }
}

/**
 * basePath function
 *
 * @return string
 */
if (! function_exists('basePath')) {
    function basePath(string $path = ""): string
    {
        return App::$ROOT_DIR . DIRECTORY_SEPARATOR . $path;
    }
}

/**
 * corePath function
 *
 * @return string
 */
if (! function_exists('corePath')) {
    function corePath(string $path = ""): string
    {
        return basePath("core". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * appPath function
 *
 * @return string
 */
if (! function_exists('appPath')) {
    function appPath(string $path = ""): string
    {
        return basePath("app". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * controllersPath function
 *
 * @return string
 */
if (! function_exists('controllersPath')) {
    function controllersPath(string $path = ""): string
    {
        return appPath("Controllers". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * migrationsPath function
 *
 * @return string
 */
if (! function_exists('migrationsPath')) {
    function migrationsPath(string $path = ""): string
    {
        return appPath("Migrations". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * providersPath function
 *
 * @return string
 */
if (! function_exists('providersPath')) {
    function providersPath(string $path = ""): string
    {
        return appPath("Providers". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * viewsPath function
 *
 * @return string
 */
if (! function_exists('viewsPath')) {
    function viewsPath(string $path = ""): string
    {
        return appPath("Views". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * partialsPath function
 *
 * @return string
 */
if (! function_exists('partialsPath')) {
    function partialsPath(string $path = ""): string
    {
        return viewsPath("partials". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * errorsPath function
 *
 * @return string
 */
if (! function_exists('errorsPath')) {
    function errorsPath(string $path = ""): string
    {
        return viewsPath("errors". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * authorize function
 * @param bool $condition
 * @param int $status
 *
 * @return void
 */
if (! function_exists('authorize')) {
    function authorize(string $condition): void
    {
        if (! $condition) throw new ForbiddenException;
    }
}

/**
 * formatText function
 * @param string $text
 * @param char $delimiter
 *
 * @return string
 */
if (! function_exists('formatText')) {
    function formatText(string $text, string $delimiter = '_'): string
    {
        return ucwords(str_replace($delimiter, ' ', $text));
    }
}

/**
 * route function
 * @param string $route
 *
 * @return void
 */
if (! function_exists('route')) {
    function route(string $name, array $params = []): string|NULL
    {
        return Router::getRoute($name, $params);
    }
}

/**
 * redirect function
 * @param string $route
 *
 * @return void
 */
if (! function_exists('redirect')) {
    function redirect(string $route = "/", array $session = [], bool $isFlash = false): void
    {
        app(Response::class)->redirect($route, $session, $isFlash);
    }
}

/**
 * back function
 *
 * @return void
 */
if (! function_exists('back')) {
    function back(array $session = [], bool $isFlash = false): void
    {
        app(Response::class)->back($session, $isFlash);
    }
}

/**
 * errors function
 * @param string $key
 *
 * @return string
 */
if (! function_exists('errors')) {
    function errors(string $key = ""): array
    {   
        $errors = session()->getFlash("errors") ?? [];

        return array_key_exists($key, $errors) ? $errors[$key] : [];
    }
}

/**
 * hasErrors function
 * @param string $key
 *
 * @return string
 */
if (! function_exists('hasErrors')) {
    function hasErrors(string $key = ""): bool
    {
        $errors = session()->getFlash("errors") ?? [];

        return array_key_exists($key, $errors);
    }
}

/**
 * validate function
 * @param string $rule
 * @param string $value
 *
 * @return string
 */
if (! function_exists('validate')) {
    function validate(array $rules): mixed
    {
        return Validator::validate($rules);
    }
}

/**
 * sanitize function
 * @param string $text
 *
 * @return string
 */
if (! function_exists('sanitize')) {
    function sanitize(string $text = ""): string
    {
        $trimmed = trim($text);
        return htmlspecialchars($trimmed);
    }
}

/**
 * bcrypt function
 * @param string $text
 * @param array $options
 *
 * @return string
 */
if (! function_exists('bcrypt')) {
    function bcrypt(string $text, array $options = ["cost" => 12])
    {
        return password_hash($text, PASSWORD_BCRYPT, $options);
    }
}

/**
 * verifyHash function
 * @param string $text
 * @param string $hash
 *
 * @return bool
 */
if (! function_exists('verifyHash')) {
    function verifyHash(string $text, string $hash): bool
    {
        return password_verify($text, $hash);
    }
}

if (! function_exists('methodParams')) {
    function methodParams(string $className, string|NULL $methodName): array|NULL
    {
        if (! $methodName) return NULL;

        $r = new ReflectionMethod($className, $methodName);
        $params = $r->getParameters();
        $return = [];
        foreach ($params as $param) {
            $return[] = [
                "name" => $param->getName(),
                "type" => $param->getType()->getName()
            ];
        }

        return $return;
    }
}

if (! function_exists('hasParameterByType')) {
    function hasParameterByType(string $class, string|NULL $method, string $type): bool
    {
        if (! $params = methodParams($class, $method)) return false;

        foreach ($params as $param) {
            if ($type === $param["type"]) return true;
        }

        return false;
    }
}

if (! function_exists('hasParameterByName')) {
    function hasParameterByName(string $class, string $method, string $name): bool
    {
        $params = methodParams($class, $method);
    
        foreach ($params as $param) {
            if ($name === $param["name"]) return true;
        }
    
        return false;
    }
}

if (! function_exists('now')) {
    function now(string $format = 'Y-m-d H:i:s'): string|false
    {
        return date($format);
    }
}
