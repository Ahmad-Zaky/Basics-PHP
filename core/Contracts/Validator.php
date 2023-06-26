<?php

namespace Core\Contracts;

interface Validator
{
    public function validate(array $rules): mixed;

    public static function isValid(mixed $rules, string $key, mixed $value): bool;

    public static function validations(): array;

    public static function required(mixed $value);

    public static function max(string $value, int $max);

    public static function min(mixed $value, int $min);

    public static function email(string $value): mixed;

    public static function url(string $value): mixed;

    public static function exists(mixed $value, string $option): bool;

    public static function unique(mixed $value, string $option): bool;

    public static function in(mixed $value, string $option): bool;

    public static function confirmed(string $key, mixed $value): bool;

    public function validated(): ?array;

    public static function addRuleError(string $rule, string $key, array $placeHolders = []): void;

    public static function addError(string $message, string $key, array $placeHolders = []): void;
}