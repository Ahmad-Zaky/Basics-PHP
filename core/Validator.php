<?php

namespace Core;

class Validator
{
    protected static $rules;

    protected static $validated;
    
    protected static $messages;
    
    protected static $errors;

    public static function validate($rules) 
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
    
    public static function isValid($rules, $key, $value)
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

    public static function validations() 
    {
        return [
            'required' => static function ($rule, $key, $value) {
                if (self::required($value)) return true;
    
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'max' => static function ($rule, $key, $value, $option) {
    
                if (self::max($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key), ":max" => $option]);
                
                return false;
            },
    
            'min' => static function ($rule, $key, $value, $option) {
    
                if (self::min($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key), ":min" => $option]);
                
                return false;
            },
    
            'email' => static function ($rule, $key, $value) {
    
                if (self::email($value)) return true;                
    
                self::addRuleError($rule, $key);
                    
                return false;
            },
    
            'url' => static function ($rule, $key, $value) {
    
                if (self::url($value)) return true;                
                
                self::addRuleError($rule, $key);
                
                return false;
            },

            'exists' => static function ($rule, $key, $value, $option) {
    
                if (self::exists($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'unique' => static function ($rule, $key, $value, $option) {
    
                if (self::unique($value, $option)) return true;                
                
                self::addRuleError($rule, $key, [":key" => formatText($key)]);
                
                return false;
            },

            'confirmed' => static function ($rule, $key, $value) {
    
                if (self::confirmed($key, $value)) return true;                
                
                self::addRuleError($rule, $key, [":key1" => formatText("{$key}_confirmation"), ":key2" => formatText($key)]);
                                
                return false;
            },
        ];
    }
    
    public static function required($value)
    {
        return strlen(trim($value) ?? "");
    }

    public static function max($value, $max)
    {
        return strlen(trim($value) ?? "") < $max;
    }

    public static function min($value, $min)
    {
        return strlen(trim($value) ?? "") > $min;
    }

    public static function email($value)
    {
        return filter_var(trim($value) ?? "", FILTER_VALIDATE_EMAIL);
    }

    public static function url($value)
    {
        return filter_var(trim($value) ?? "", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
    }

    public static function exists($value, $option)
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

    public static function unique($value, $option)
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

    public static function confirmed($key, $value)
    {
        return request("{$key}_confirmation") === $value;
    }


    public static function validated()
    {
        return self::$validated;
    }

    protected static function getKeyValue(string $key)
    {
        return trim(request($key));
    }

    protected static function messages($rule)
    {
        if (empty(self::$messages)) {
            self::$messages = require corePath("error_messages.php");
        }

        return self::$messages[$rule] ?? NULL;
    }

    protected static function getRuleMessage($rule, $placeHolders = [])
    {
        if (! $errorMsg = self::messages($rule)) {
            return '';
        }

        foreach($placeHolders as $placeHolder => $val){
            $errorMsg = str_replace($placeHolder, $val, $errorMsg);
        }

        return $errorMsg;
    }

    public static function addRuleError($rule, $key, $placeHolders = []) 
    {
        self::$errors[$key][$rule] = self::getRuleMessage($rule, $placeHolders);
    }

    protected static function getMessage($errorMsg, $placeHolders = [])
    {
        if (! $errorMsg) {
            return '';
        }

        foreach($placeHolders as $placeHolder => $val){
            $errorMsg = str_replace($placeHolder, $val, $errorMsg);
        }

        return $errorMsg;
    }

    public static function addError($message, $key, $placeHolders = []) 
    {
        self::$errors[$key][] = self::getMessage($message, $placeHolders);
    }

    protected static function passErrorsToSession() {
        session()->setFlash("errors", self::$errors);
    }
}