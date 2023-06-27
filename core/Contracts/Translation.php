<?php

namespace Core\Contracts;

interface Translation
{
    public function setLocal(string $local): void;

    public function getLocal(): string;

    public function trans(?string $toTranslate, array $replace, ?string $locale): ?string;
}