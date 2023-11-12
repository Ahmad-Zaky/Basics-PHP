<?php

namespace Core\Contracts;

use Core\Container;

interface Application
{
    public function boot(): void;

    public function run(): void;

    public static function setContainer($container): void;

    public static function container(): Container;

    public function bind(string $key, mixed $resolver): mixed;

    public function bindList(array $binds): mixed;

    public function singleton(string $key, mixed $resolver): mixed;

    public function singletonList(array $binds): mixed;

    public static function resolve(string $key): mixed;

    public function setLocal(string $local): void;

    public function getLocal(): string;

    public function getNamespace(): string;

    public function getPath(): string;
}