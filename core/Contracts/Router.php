<?php

namespace Core\Contracts;

interface Router
{
    public static function GET(string $uri, array $controller): self;

    public static function POST(string $uri, array $controller): self;

    public static function PUT(string $uri, array $controller): self;

    public static function PATCH(string $uri, array $controller): self;

    public static function DELETE(string $uri, array $controller): self;

    public function route(): void;

    public static function loadRoutes(): void;

    public static function registerProviders(): void;

    public static function execute(array $route): void;

    public static function getRoute(string $name, array $params = []): ?string;

    public function middleware(mixed $middleware): self;

    public function name(string $name): self;

    public static function getUri(): ?string;

    public static function bindParams(string $uri, array $params = []): string;

    public static function getRouteUri(string $uri = ""): string;

    public static function getMethod(): string;

    public static function getRoutes(): array;

    public static function validateParams(array $uriParams, array $params): void;

    public static function toQueryString(array $params = [], array $except = []): string;

    public function urlIs(string $value): bool;

    public function urlIn(array $routes): bool;
}