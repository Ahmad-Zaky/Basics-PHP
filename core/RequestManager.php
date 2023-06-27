<?php

namespace Core;

use Core\Contracts\{Request, Session};

class RequestManager implements Request
{
    protected array $body;

    protected array $uriParams;

    function __construct(protected Session $session) {
        $this->setBody();
        $this->setOldBody();
    }

    public function setOldBody(): void
    {
        if (in_array($this->method(), ["POST", "PUT", "PATCH"])) {
            if (! isset($_POST)) return;

            $this->session->setFlash("old", $_POST);
        }
    }

    public function setBody(): void
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

    public function get(string $key): string
    {
        return $this->body[$key] ?? NULL;
    }

    public function set(string $key, string $value): void
    {
        $this->body[$key] = $value;
    }

    public function path(): string
    {
        $path = $_SERVER["REQUEST_URI"] ?? '/';
        if ($position = strpos($path, '?') === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }    

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    public function isPatch(): bool
    {
        return $this->method() === 'PATCH';
    }

    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }
    
    public function isReading(string $method): bool
    {
        return in_array($method, ['HEAD', 'GET', 'OPTIONS']);
    }

    public function method(): string|NULL
    {
        return $this->body["_method"] ?? $_SERVER["REQUEST_METHOD"] ?? NULL;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function wantsJson(): bool
    {
        $acceptHeader = $_SERVER['ACCEPT'] ?? $_SERVER['HTTP_ACCEPT'] ?? '';
        return (
            strpos($acceptHeader, '/json') !== false ||
            strpos($acceptHeader, '+json') !== false
        );
    }

    public function isJson(): bool
    {
        $contentHeader = $_SERVER['CONTENT_TYPE'] ?? '';

        return (
            strpos($contentHeader, '/json') !== false ||
            strpos($contentHeader, '+json') !== false
        );
    }

    public function cookie($key): string
    {
        return cookie()->get($key);
    }
}