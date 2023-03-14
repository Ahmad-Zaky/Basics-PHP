<?php

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
            require controllersPath() . $routes[$uri] .".php";
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
