<?php

use Core\{App, Auth, Session, Response, Router, Request, Config, View, Validator};

/**
 * config function
 *
 * @return void
 */
if (! function_exists("config")) {
    function config($keys = "")
    {
        return app(Config::class)->get($keys);
    }
}

/**
 * signin function
 *
 * @return void
 */
if (! function_exists("signin")) {
    function signin($user = NULL)
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
    function signout()
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
    function auth($key = "")
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
    function guest()
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
    function request($key = "")
    {
        $request = app(Request::class);

        if ($key) {
            return $request->body()[$key] ?? NULL;
        }

        return $request;
    }
}

/**
 * csrfToken function
 *
 * @return string
 */
if (! function_exists("csrfToken")) {
    function csrfToken()
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
    function generateToken()
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
    function csrfInput()
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
    function requestMethod()
    {    
        return strtoupper(request()->method());
    }
}

/**
 * distroyCsrfToken function
 *
 * @return void
 */
if (! function_exists("distroyCsrfToken")) {
    function distroyCsrfToken()
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
    function session($key = "")
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
    function app($key = "")
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
    function old($key, $default = "")
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
    function showErrors()
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
	function d($dump='')
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
    function dd()
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
    function dump($dump='')
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
    function urlIs($value = '')
    {
        return app(Router::class)->urlIs($value);
    }
}

/**
 * urlIn function
 *
 * @param [type] $value
 * @return void
 */
if (! function_exists('urlIn')) {
    function urlIn(array $routes)
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
    function abort(int $code = 404)
    {
        app(Request::class)->abort($code);
    }
}

/**
 * view function
 *
 * @return string
 */
if (! function_exists('view')) {
    function view($path = "", $attributes = [])
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
    function basePath($path = "")
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
    function corePath($path = "")
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
    function appPath($path = "")
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
    function controllersPath($path = "")
    {
        return appPath("Controllers". DIRECTORY_SEPARATOR . $path);
    }
}

/**
 * viewsPath function
 *
 * @return string
 */
if (! function_exists('viewsPath')) {
    function viewsPath($path = "")
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
    function partialsPath($path = "")
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
    function errorsPath($path = "")
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
    function authorize($condition, $status = Response::HTTP_FORBIDDEN)
    {
        if (! $condition) app(Request::class)->abort($status);
    }
}

/**
 * route function
 * @param string $route
 *
 * @return void
 */
if (! function_exists('route')) {
    function route($name, $params = [])
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
    function redirect($route = "/", $session = [])
    {
        Response::redirect($route, $session);
    }
}

/**
 * back function
 *
 * @return void
 */
if (! function_exists('back')) {
    function back($session = [])
    {
        Response::back($session);
    }
}

/**
 * errors function
 * @param string $key
 *
 * @return string
 */
if (! function_exists('errors')) {
    function errors($key = "")
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
    function hasErrors($key = "")
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
    function validate($rules)
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
    function sanitize($text = "")
    {
        return htmlspecialchars($text);
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
    function bcrypt($text, $options = ["cost" => 12])
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
    function verifyHash($text, $hash)
    {
        return password_verify($text, $hash);
    }
}