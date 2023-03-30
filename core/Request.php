<?php

namespace Core;

class Request
{
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
    
    public function method()
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    public function body() 
    {
        $body = [];
        if ($this->method() === "get") {
            foreach ($_GET as $key => $_) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === "post") {
            foreach ($_POST as $key => $_) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}