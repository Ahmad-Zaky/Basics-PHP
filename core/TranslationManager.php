<?php

namespace Core;

use Core\Contracts\Config;
use Core\Contracts\Translation;
use Exception;

class TranslationManager implements Translation
{
    protected static string $local = '';

    function __construct(protected Config $config)
    {
        self::$local = $config->get('app.local');
    }

    public function setLocal(?string $local): void
    {
        if (! $local) return;

        if ($local && ! in_array($local, $this->config->get('app.locals'))) {
            throw new Exception(__("Unknown language."));
        }

        self::$local = $local;
    }

    public function getLocal(): string
    {
        return self::$local;
    }

    public function trans(?string $toTranslate, array $replaces, ?string $local): ?string
    {
        $transParts = explode(".", $toTranslate);

        $file = $transParts[0]; unset($transParts[0]);
        if (empty($transParts)) {
            return NULL;
        }

        if ($translated = $this->resolve($file, $transParts[1], $local ?? self::$local)) {
            return $this->replace($translated, $replaces);
        }

        $fallbackLocal = $this->config->get('app.local_fallback');
        if ($translated = $this->resolve($file, $transParts[1], $fallbackLocal)) {
            return $this->replace($translated, $replaces);
        }

        return NULL;
    }

    protected function resolve(string $file, string $value, string $local): ?string
    {
        $translations = require localPath($local . DIRECTORY_SEPARATOR . $file);
        $found = $translations[$value] ?? NULL;

        if (! $found) {
            return NULL;
        }

        return $found;
    }

    public function replace(string $message, array $replaces = []): string
    {
        $formattedMessage = $message;
        foreach($replaces as $placeHolder => $val){
            $formattedMessage = str_replace(":". $placeHolder, $val, $message);
        }

        return $formattedMessage;
    }
}
