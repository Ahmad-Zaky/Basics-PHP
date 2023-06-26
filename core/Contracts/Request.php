<?php

namespace Core\Contracts;

interface Request
{
    public function setOldBody(): void;

    public function setBody(): void;

    public function get(string $key): string;

    public function set(string $key, string $value): void;

    public function path(): string;

    public function isGet(): bool;

    public function isPost(): bool;

    public function isPut(): bool;

    public function isPatch(): bool;

    public function isDelete(): bool;

    public function method(): string|NULL;

    public function body(): array;

    public function wantsJson(): bool;

    public function isJson(): bool;

    public function cookie($key): string;
}