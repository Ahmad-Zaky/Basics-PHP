<?php

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
 * init function
 *
 * @return void
 */
if (! function_exists("init")) {
    function init()
    {
        setRequest();
        setErrors();
        setOld();
        config();
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
    function config()
    {
        global $config;
        
        return ($config)
            ? $config
            : $config = require appPath("config.php");
    }
}

/**
 * auth function
 *
 * @return void
 */
if (! function_exists("auth")) {
    function auth()
    {
        global $auth, $config;
        
        $db = new DB($config["db"]);
        
        return ($auth)
            ? $auth
            : ($auth = $db->query("SELECT * FROM users WHERE id = :id", ["id" => 1])->findOrFail());
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
 * config function
 *
 * @return void
 */
if (! function_exists("config")) {
    function config($key = "")
    {
        global $config;

        if ($key) {
            isset($config[$key]) ? $config[$key] : NULL;
        }

        return $config;
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

        require appPath("views". DIRECTORY_SEPARATOR . $path);
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
        return appPath("controllers". DIRECTORY_SEPARATOR . $path);
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
        return appPath("views". DIRECTORY_SEPARATOR . $path);
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
 * redirect function
 * @param string $route
 *
 * @return void
 */
if (! function_exists('redirect')) {
    function redirect($route = "/")
    {
        Response::redirect($route);
    }
}

/**
 * back function
 *
 * @return void
 */
if (! function_exists('back')) {
    function back()
    {
        Response::back();
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