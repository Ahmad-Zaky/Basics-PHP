<?php

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
            isset($request[$key]) ? $request[$key] : NULL;
        }

        return $request;
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

        global $old;
        
        $old = $_SESSION['old'];

        unset($_SESSION['old']);
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

        require errorsPath() . "{$code}.view.php";

        die;
    }
}

/**
 * route function
 *
 * @param array $routes
 * @param string $uri
 * @return void
 */
if (! function_exists('route')) {
    function route(array $routes, string $uri = "/")
    {
        if (array_key_exists($uri, $routes)) {
            require controllersPath() . str_replace(".", DIRECTORY_SEPARATOR, $routes[$uri]) .".php";
        } else abort();
    }
}

/**
 * partialsPath function
 *
 * @return string
 */
if (! function_exists('partialsPath')) {
    function partialsPath()
    {
        return "views". DIRECTORY_SEPARATOR ."partials". DIRECTORY_SEPARATOR;
    }
}

/**
 * viewsPath function
 *
 * @return string
 */
if (! function_exists('viewsPath')) {
    function viewsPath()
    {
        return "views". DIRECTORY_SEPARATOR;
    }
}

/**
 * controllersPath function
 *
 * @return string
 */
if (! function_exists('controllersPath')) {
    function controllersPath()
    {
        return "controllers". DIRECTORY_SEPARATOR;
    }
}

/**
 * errorsPath function
 *
 * @return string
 */
if (! function_exists('errorsPath')) {
    function errorsPath()
    {
        return "views". DIRECTORY_SEPARATOR ."errors". DIRECTORY_SEPARATOR;
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
        header("Location: /notes");
        
        exit();
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
        header("Location: {$_SERVER['HTTP_REFERER']}");

        exit();
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