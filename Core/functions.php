<?php

use Core\App;
use Core\Container;
use Core\DB;
use Core\Response;
use Core\Router;
use Core\Validator;

/**
 * autoload function
 *
 * @return void
 */
if (! function_exists("autoload")) {
    function autoload()
    {
        spl_autoload_register(function ($class) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

            require basePath("{$class}.php");
        });
    }
}

/**
 * run function
 *
 * @return void
 */
if (! function_exists("run")) {
    function run()
    {
        setRequest();
        setErrors();
        setOld();
        config();
        registerServices();
        auth();

        Router::route();
    }
}

/**
 * config function
 *
 * @return void
 */
if (! function_exists("config")) {
    function config($key = "")
    {
        global $config;
        
        if (empty($config)) $config = require appPath("config.php");

        if ($key) {
            return isset($config[$key]) ? $config[$key] : NULL;
        }

        return $config;
    }
}

/**
 * signin function
 *
 * @return void
 */
if (! function_exists("signin")) {
    function signin($user = "")
    {
        $_SESSION["user"] = [
            "name" => $user["name"],
            "email" => $user["email"]
        ];

        session_regenerate_id();
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
        $_SESSION = [];
        session_destroy();

        $params = session_get_cookie_params();
        setcookie("PHPSESSID", "", time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
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
        global $auth;
        
        if (empty($auth)) {
            $db = app(DB::class);

            $auth = ($email = session("user")["email"] ?? NULL)
                ? $db->query("SELECT * FROM users WHERE email = :email", ["email" => $email])->findOrFail()
                : NULL;
        }

        if ($key) {
            return isset($auth[$key]) ? $auth[$key] : NULL;
        }

        return $auth;
    }
}

/**
 * setRequest function
 *
 * @return void
 */
if (! function_exists("setRequest")) {
    function setRequest()
    {
        global $request;

        $request = $_REQUEST;
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
        global $request;

        if ($key) {
            return isset($request[$key]) ? $request[$key] : NULL;
        }

        return $request;
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
        if ($key) {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
        }

        return $_SESSION;
    }
}

/**
 * registerServices function
 *
 * @return void
 */
if (! function_exists("registerServices")) {
    function registerServices() 
    {
        $container = new Container;

        // Register DB object
        $container->bind(DB::class, function () {
            return new DB(config("db"));
        });

        App::setContainer($container);
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

        return App::container();
    }
}

/**
 * setErrors function
 *
 * @return void
 */
if (! function_exists("setErrors")) {
    function setErrors()
    {        
        if (isset($_SESSION["errors"])) {
            global $errors;

            $errors = $_SESSION["errors"];
        
            unset($_SESSION['errors']);
        }
    }
}

/**
 * setOld function
 *
 * @return void
 */
if (! function_exists("setOld")) {
    function setOld()
    {
        if (in_array($_SERVER["REQUEST_METHOD"], ["POST", "PUT", "PATCH"])) {
            if (! isset($_POST)) return;
    
            $_SESSION['old'] = $_POST;

            return;
        }

        if (isset($_SESSION['old'])) {
            global $old;
            
            $old = $_SESSION['old'];
    
            unset($_SESSION['old']);
        }
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
        global $old;

        if (isset($old[$key])) {
            return $old[$key];
        }

        return $default;
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
    function urlIs($value='')
    {
        return $_SERVER["REQUEST_URI"] === $value;
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
        http_response_code($code);

        require view("errors.{$code}");

        exit;
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
        extract($attributes);

        $path = str_replace(".", DIRECTORY_SEPARATOR, $path) .".view.php";

        require appPath("Views". DIRECTORY_SEPARATOR . $path);
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
        return BASE_PATH . $path;
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
        return basePath("Core". DIRECTORY_SEPARATOR . $path);
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
        return basePath("App". DIRECTORY_SEPARATOR . $path);
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
        if (! $condition) abort($status);
    }
}

/**
 * route function
 * @param string $route
 *
 * @return void
 */
if (! function_exists('route')) {
    function route($name)
    {
        return Router::getRoute($name);
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
        global $errors;

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
        global $errors;

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