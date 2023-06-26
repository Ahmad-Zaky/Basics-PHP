<?php

namespace Core\Contracts;

interface ModelContract
{
    public static function calledClass(): string;

    public static function calledClassInstance(): self;

    public static function getTable(): string;

    public function getAttributes(): array;

    public function toArray(): array;

    public function setAttributes(array $data): self;

    public function __get(string $key): mixed;

    public function __set(string $key, mixed $value): void;

    public function has(string $key): bool;

    public static function all(): array;

    public static function find(int $id): mixed;

    public static function findOrFail(int $id): mixed;

    public static function create(array $data): mixed;

    public function update(array $data): mixed;

    public function delete(): mixed;

    public function get(): array;

    public function first(): self|NULL;

    public function firstOrFail(): mixed;

    public function query(mixed $statement, array $params): self;
}