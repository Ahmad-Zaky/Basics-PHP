<?php

namespace Core\Contracts;

interface Session
{
    public function set(string $key, mixed $value): void;

    public function get(string $key): mixed;

    public function remove(string $key): void;

    public function markFlashMessages(): void;

    public function setFlash(string $key, mixed $message): void;

    public function getFlash($key): mixed;

    public function flush(): void;

    public function destroy(): void;

    public function csrf(): string;

    public function genCsrf(): string;
    
    public function csrfInput(): string;

    public function destroyCsrf(): void;

    public function signin(Authenticatable $user): void;
}