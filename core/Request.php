<?php

namespace Core;

class Request
{
    protected array $body;

    protected array $uriParams;

    function __construct() {
        $this->setBody();
        $this->setOldBody();
    }
    
    public function setOldBody()
    {
        if (in_array($this->method(), ["post", "put", "patch"])) {
            if (! isset($_POST)) return;

            session()->setFlash("old", $_POST);
        }
    }

    public function setBody() 
    {
        $this->body = [];

        if ($this->method() === "get") {
            foreach ($_GET as $key => $_) {
                $this->body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if (in_array($this->method(), ["post", "put", "patch"])) {
            foreach ($_POST as $key => $_) {
                $this->body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }

    public function get($key) 
    {
        return $this->body[$key] ?? NULL;
    }

    public function set($key, $value)
    {
        $this->body[$key] = $value;
    }

    public function path() 
    {
        $path = $_SERVER["REQUEST_URI"] ?? '/';
        if ($position = strpos($path, '?') === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function isGet() 
    {
        return $this->method() === 'get';
    }    

    public function isPost() 
    {
        return $this->method() === 'post';
    }

    public function isPut() 
    {
        return $this->method() === 'put';
    }

    public function isPatch() 
    {
        return $this->method() === 'patch';
    }

    public function isDelete() 
    {
        return $this->method() === 'delete';
    }
    
    public function method()
    {
        return isset($_POST["_method"]) ? strtolower($_POST["_method"]) : strtolower($_SERVER["REQUEST_METHOD"]);
    }

    public function body() 
    {
        return $this->body;
    }

    public function abort(int $code = 404) 
    {
        http_response_code($code);

        require view("errors.{$code}");

        exit;
    }
}