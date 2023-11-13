<?php

use Core\App;
use Core\CommandColors;
use Core\Contracts\{
    Cookie,
    Config,
    Auth,
    Response,
    Request,
    Migration,
    Event,
    Session,
    Validator,
    View,
};
use Core\Exceptions\FileNotFoundException;
use Core\Facades\{Route, Translation};

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
 * logging function
 *
 * @return void
 */
function logging(string $message, string $color = CommandColors::GREEN_COLOR, bool $withDate = true): void
{
    app(Log::class)->print($message, $color, $withDate);
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
 * make function
 *
 * @return void
 */
if (! function_exists("make")) {
    function make(string $makeable, string $name): void
    {
        app(Make::class)->handle($makeable, $name);
    }
}

/**
 * getMakeable function
 *
 * @return string
 */
if (! function_exists("getMakeable")) {
    function getMakeable(array $arguments): string
    {
        return $arguments[1];
    }
}

/**
 * getMakeableName function
 *
 * @return string
 */
if (! function_exists("getMakeableName")) {
    function getMakeableName(array $arguments): string
    {
        return $arguments[2];
    }
}

/**
 * migrate function
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
    function old(string $key, mixed $default = NULL): mixed
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
        return Route::urlIs($value);
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
        return Route::urlIn($routes);
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
 * localPath function
 *
 * @return string
 */
if (! function_exists('localPath')) {
    function localPath(string $path = ""): string
    {
        return appPath("Localizations". DIRECTORY_SEPARATOR ."{$path}.php");
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
        return Route::getRoute($name, $params);
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
        return app(Validator::class)->validate($rules);
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
    function methodParams(string|NULL $methodName = '__invoke', ?Closure $closure = null): array|NULL
    {
        if (! $methodName) return NULL;
        
        $reflectionMethod = ! $closure && $methodName !== '__invoke'
            ? new ReflectionFunction($methodName)
            : new ReflectionMethod($closure, '__invoke');

        $params = $reflectionMethod->getParameters();
        $return = [];
        foreach ($params as $param) {
            $return[] = [
                "name" => $param->getName(),
                "type" => $param->getType()?->getName()
            ];
        }

        return $return;
    }
}

if (! function_exists('classMethodParams')) {
    function classMethodParams(string $className, string|NULL $methodName): array|NULL
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

if (! function_exists('hasMethodParameterByType')) {
    function hasMethodParameterByType(?string $method = '__invoke', ?string $type = null, ?Closure $closure = null): bool
    {
        if (! $params = methodParams($method, $closure)) return false;        

        foreach ($params as $param) {
            if ($type === $param["type"]) return true;
        }

        return false;
    }
}

if (! function_exists('hasParameterByType')) {
    function hasParameterByType(string $class, string|NULL $method, string $type): bool
    {
        if (! $params = classMethodParams($class, $method)) return false;

        foreach ($params as $param) {
            if ($type === $param["type"]) return true;
        }

        return false;
    }
}

if (! function_exists('hasParameterByName')) {
    function hasParameterByName(string $class, string $method, string $name): bool
    {
        $params = classMethodParams($class, $method);
    
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

if (! function_exists('__')) {
    function __(
        string $key = null,
        array $replace = [],
        string $locale = null,
        string $file = 'general'
    ): string {
        $toTranslate = "{$file}.{$key}";
        if (
            ! $translated = Translation::trans(
                $toTranslate,
                $replace,
                $locale
            )
        ) {
            return Translation::replace($key, $replace); 
        }

        return $translated;
    }
}

/**
 * Determine if a given string starts with a given substring.
 *
 * @param  string  $haystack
 * @param  string|iterable<string>  $needles
 * @return bool
 */
if (! function_exists('strStartsWith')) {
    function strStartsWith(string $haystack, string|array $needles): bool
    {
        if (! is_iterable($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ((string) $needle !== '' && str_starts_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Replace the first occurrence of a given value in the string.
 *
 * @param  string  $search
 * @param  string  $replace
 * @param  string  $subject
 * @return string
 */
if (! function_exists('strReplaceFirst')) {
    function strReplaceFirst(string $search, string $replace, string $subject): string
    {
        $search = (string) $search;
    
        if ($search === '') {
            return $subject;
        }
    
        $position = strpos($subject, $search);
    
        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }
    
        return $subject;
    }
}

if (! function_exists('version')) {
    function version(): string
    {
        return app()->version();
    }
}

/**
 * Determine if the given path is a directory.
 *
 * @param  string  $directory
 * @return bool
 */
if (! function_exists('isDirectory')) {
    function isDirectory(string $directory): bool
    {
        return is_dir($directory);
    }
}

/**
 * Create a directory.
 *
 * @param  string  $path
 * @param  int  $mode
 * @param  bool  $recursive
 * @param  bool  $force
 * @return bool
 */
if (! function_exists('makeDirectory')) {
    function makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }
    
        return mkdir($path, $mode, $recursive);
    }
}

/**
 * Determine if the given path is readable.
 *
 * @param  string  $path
 * @return bool
 */
if (! function_exists('isReadable')) {
    function isReadable(string $path): bool
    {
        return is_readable($path);
    }
}

/**
 * Determine if the given path is writable.
 *
 * @param  string  $path
 * @return bool
 */
if (! function_exists('isWritable')) {
    function isWritable(string $path): bool
    {
        return is_writable($path);
    }
}
/**
 * Determine if the given path is writable.
 *
 * @param  string  $path
 * @return bool
 */
if (! function_exists('isWritable')) {
    function isWritable(string $path): bool
    {
        return is_writable($path);
    }
}

/**
 * Write the contents of a file.
 *
 * @param  string  $path
 * @param  string  $contents
 * @param  bool  $lock
 * @return int|bool
 */
if (! function_exists('putFile')) {
    function putFile(string $path, string $contents, bool $lock = false): int|bool
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }
}

/**
 * Get the contents of a file.
 *
 * @param  string  $path
 * @param  bool  $lock
 * @return string
 *
 * @throws \Core\Exceptions\FileNotFoundException
 */
if (! function_exists('getFile')) {
    function getFile(string $path, bool $lock = false): string
    {
        if (isFile($path)) {
            return $lock ? sharedGetFile($path) : file_get_contents($path);
        }
    
        throw new FileNotFoundException("File does not exist at path {$path}.");
    }
}

/**
 * Determine if the given path is a file.
 *
 * @param  string  $file
 * @return bool
 */
if (! function_exists('isFile')) {
    function isFile(string $file): bool
    {
        return is_file($file);
    }
}

/**
 * Get contents of a file with shared access.
 *
 * @param  string  $path
 * @return string
 */
if (! function_exists('sharedGetFile')) {
    function sharedGetFile(string $path): string
    {
        $contents = '';
    
        $handle = fopen($path, 'rb');
    
        if ($handle) {
            try {
                if (flock($handle, LOCK_SH)) {
                    clearstatcache(true, $path);
    
                    $contents = fread($handle, getFileSize($path) ?: 1);
    
                    flock($handle, LOCK_UN);
                }
            } finally {
                fclose($handle);
            }
        }
    
        return $contents;
    }
}

/**
 * Get the file size of a given file.
 *
 * @param  string  $path
 * @return int
 */
if (! function_exists('getFileSize')) {
    function getFileSize(string $path): int
    {
        return filesize($path);
    }
}

/**
 * Get the plural of a word.
 *
 * @param  string  $word
 * @param  bool  $toLower
 * @return string
 */
if (! function_exists('pluralize')) {
    function pluralize(string $word, bool $toLower = false): string
    {
        $pluralizer = new \anytizer\pluralizer();

        return $toLower 
            ? strtolower( $pluralizer->pluralize($word))
            :  $pluralizer->pluralize($word);
    }
}

/**
 * Get the date prefix for the migration.
 *
 * @return string
 */
if (! function_exists('getDatePrefix')) {
    function getDatePrefix(string $format = 'Y_m_d_His'): string
    {
        return date($format);
    }
}

