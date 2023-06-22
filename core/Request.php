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
        if (in_array($this->method(), ["POST", "PUT", "PATCH"])) {
            if (! isset($_POST)) return;

            session()->setFlash("old", $_POST);
        }
    }

    public function setBody() 
    {
        $this->body = [];

        if ($this->method() === "GET") {
            foreach ($_GET as $key => $_) {
                $this->body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if (in_array($this->method(), ["POST", "PUT", "PATCH", "DELETE"])) {
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
        return $this->method() === 'GET';
    }    

    public function isPost() 
    {
        return $this->method() === 'POST';
    }

    public function isPut() 
    {
        return $this->method() === 'PUT';
    }

    public function isPatch() 
    {
        return $this->method() === 'PATCH';
    }

    public function isDelete() 
    {
        return $this->method() === 'DELETE';
    }
    
    public function method()
    {
        return $this->body["_method"] ?? $_SERVER["REQUEST_METHOD"] ?? NULL;
    }

    public function body() 
    {
        return $this->body;
    }

    public function wantsJson()
    {
        $acceptHeader = $_SERVER['ACCEPT'] ?? $_SERVER['HTTP_ACCEPT'] ?? '';
        return (
            strpos($acceptHeader, '/json') !== false ||
            strpos($acceptHeader, '+json') !== false
        );
    }

    public function isJson()
    {
        $contentHeader = $_SERVER['CONTENT_TYPE'] ?? '';

        return (
            strpos($contentHeader, '/json') !== false ||
            strpos($contentHeader, '+json') !== false
        );
    }

    public function cookie($key) 
    {
        return cookie()->get($key);
    }
}