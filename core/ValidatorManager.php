<?php

namespace Core;

use Core\Contracts\DB;
use Core\Contracts\Validator;

class ValidatorManager implements Validator
{
    protected static $rules;

    protected static $validated;
    
    protected static $messages;
    
    protected static $errors;

    public function validate(array $rules): mixed
    {
        self::$rules = $rules;

        foreach (self::$rules as $key => $keyRules) {            
            $value = self::getKeyValue($key);

            self::$validated[$key] = $value;

            self::isValid($keyRules, $key, $value);
        }

        if (empty(self::$errors)) return true;

        self::passErrorsToSession();

        back();
    }
    
    public static function isValid(mixed $rules, string $key, mixed $value): bool
    {
        $rules = is_array($rules) ? $rules : explode("|", $rules);
        foreach ($rules as $rule) {
            if (is_callable($rule)) {
                ($rule)($key, $value, function (string $message, array $placeHolders = []) use ($key) {
                    self::addError($message, $key, $placeHolders);
                });

                continue;
            }

            $option = isset(explode(":", $rule)[1]) ? explode(":", $rule)[1] : NULL;
            $rule = explode(":", $rule)[0];

            $caller = isset(self::validations()[$rule]) ? self::validations()[$rule] : NULL;
            
            if ($caller) $caller($rule, $key, $value, $option);
        }

        return ! empty($errors);
    }

    public static function validations(): array
    {
        return [
            'required' => static function (string $rule, string $key, mixed $value): bool {
                if (self::required($value)) return true;
    
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'max' => static function (string $rule, string $key, mixed $value, string $option): bool {
    
                if (self::max($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key), ":max" => $option]);
                
                return false;
            },
    
            'min' => static function (string $rule, string $key, mixed $value, string $option): bool {
    
                if (self::min($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key), ":min" => $option]);
                
                return false;
            },
    
            'email' => static function (string $rule, string $key, mixed $value): bool {
    
                if (self::email($value)) return true;                
    
                self::addRuleError($rule, $key);
                    
                return false;
            },
    
            'url' => static function (string $rule, string $key, mixed $value): bool {
    
                if (self::url($value)) return true;                
                
                self::addRuleError($rule, $key);
                
                return false;
            },

            'exists' => static function (string $rule, string $key, mixed $value, string $option): bool {
    
                if (self::exists($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'unique' => static function (string $rule, string $key, mixed $value, string $option): bool {
    
                if (self::unique($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'in' => static function (string $rule, string $key, mixed $value, string $option): bool {
    
                if (self::in($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'confirmed' => static function (string $rule, string $key, mixed $value): bool {
    
                if (self::confirmed($key, $value)) return true;                
                
                self::addRuleError($rule, $key, [":key1" => formatText("{$key}_confirmation"), ":key2" => formatText($key)]);
                                
                return false;
            },
        ];
    }
    
    public static function required(mixed $value)
    {
        return strlen(trim($value) ?? "");
    }

    public static function max(string $value, int $max)
    {
        return strlen(trim($value) ?? "") < $max;
    }

    public static function min(mixed $value, int $min)
    {
        return strlen(trim($value) ?? "") > $min;
    }

    public static function email(string $value): mixed
    {
        return filter_var(trim($value) ?? "", FILTER_VALIDATE_EMAIL);
    }

    public static function url(string $value): mixed
    {
        return filter_var(trim($value) ?? "", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
    }

    public static function exists(mixed $value, string $option): bool
    {
        $table = explode(",", $option)[0];
        $column = explode(",", $option)[1] ?? "id";

        $db = app(DB::class);
        if (! $db->tableExists($table) || ! $db->columnExists($table, $column)) {
            return false;
        }

        $item = $db->query("SELECT {$column} FROM {$table} WHERE {$column} = ':value'", [
            "value" => $value,
        ])->find();

        return $item !== false;
    }

    public static function unique(mixed $value, string $option): bool
    {
        $table = explode(",", $option)[0];
        $column = explode(",", $option)[1] ?? "id";
        $except = explode(",", $option)[2] ?? NULL;

        $db = app(DB::class);
        if (! $db->tableExists($table) || ! $db->columnExists($table, $column)) {
            return false;
        }

        $params = ["value" => trim($value)];
        
        $q = "SELECT {$column} FROM {$table} WHERE {$column} = :value";
        if ($except) {
            $params["except"] = trim($except);
            $q .= " AND {$column} != :except";
        }

        $item = $db->query($q, $params)->find();

        return $item === false;
    }

    public static function in(mixed $value, string $option): bool
    {
        return empty($value) || in_array($value, explode(",", $option));
    }

    public static function confirmed(string $key, mixed $value): bool
    {
        return request("{$key}_confirmation") === $value;
    }

    public function validated(): ?array
    {
        return self::$validated;
    }

    protected static function getKeyValue(string $key): mixed
    {
        return trim(request($key));
    }

    protected static function messages(string $rule): ?string
    {
        if (empty(self::$messages)) {
            $local = app()->getLocal();
            self::$messages = require localPath($local . DIRECTORY_SEPARATOR ."validation");
        }

        return self::$messages[$rule] ?? NULL;
    }

    protected static function getRuleMessage(string $rule, array $placeHolders = []): string
    {
        if (! $errorMsg = self::messages($rule)) {
            return '';
        }

        foreach($placeHolders as $placeHolder => $val){
            $errorMsg = str_replace($placeHolder, $val, $errorMsg);
        }

        return $errorMsg;
    }

    public static function addRuleError(string $rule, string $key, array $placeHolders = []): void
    {
        self::$errors[$key][$rule] = self::getRuleMessage($rule, $placeHolders);
    }

    protected static function getMessage(string $errorMsg, array $placeHolders = []): string
    {
        if (! $errorMsg) {
            return '';
        }

        foreach($placeHolders as $placeHolder => $val){
            $errorMsg = str_replace($placeHolder, $val, $errorMsg);
        }

        return $errorMsg;
    }

    public static function addError(string $message, string $key, array $placeHolders = []): void
    {
        self::$errors[$key][] = self::getMessage($message, $placeHolders);
    }

    protected static function passErrorsToSession(): void
    {
        session()->setFlash("errors", self::$errors);
    }
}