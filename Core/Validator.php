<?php

namespace Core;

class Validator
{
    protected static $rules;

    protected static $validated;
    
    protected static $messages;

    public static function validate($rules) 
    {
        self::$rules = $rules;
        
        foreach (self::$rules as $key => $keyRules) {            
            $value = self::getKeyValue($key);
            
            self::$validated[$key] = $value;
            
            self::isValid($keyRules, $key, $value);
        }
        
        global $errors; if (empty($errors)) return true;

        self::passErrorsToSession();

        back();
    }
    
    public static function isValid($rules, $key, $value)
    {
        foreach (explode("|", $rules) as $rule) {
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
    
                self::addError($rule, $key, [":key" => $key]);
                
                return false;
            },
    
            'max' => static function ($rule, $key, $value, $option) {
    
                if (self::max($value, $option)) return true;                
                
                self::addError($rule, $key, [":key" => $key, ":max" => $option]);
                
                return false;
            },
    
            'min' => static function ($rule, $key, $value, $option) {
    
                if (self::min($value, $option)) return true;                
                
                self::addError($rule, $key, [":key" => $key, ":min" => $option]);
                
                return false;
            },
    
            'email' => static function ($rule, $key, $value) {
    
                if (self::email($value)) return true;                
    
                self::addError($rule, $key);
                    
                return false;
            },
    
            'url' => static function ($rule, $key, $value) {
    
                if (self::url($value)) return true;                
                
                self::addError($rule, $key);
                
                return false;
            },

            'exists' => static function ($rule, $key, $value, $option) {
    
                if (self::exists($value, $option)) return true;                
                
                self::addError($rule, $key, [":key" => $key]);
                
                return false;
            },

            'unique' => static function ($rule, $key, $value, $option) {
    
                if (self::unique($value, $option)) return true;                
                
                self::addError($rule, $key, [":key" => $key]);
                
                return false;
            },

            'confirmed' => static function ($rule, $key, $value) {
    
                if (self::confirmed($key, $value)) return true;                
                
                self::addError($rule, $key, [":key1" => "{$key}_confirmation", ":key2" => $key]);
                                
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
        return trim($_POST[$key]);
    }

    protected static function messages($rule)
    {
        if (empty(self::$messages)) {
            self::$messages = require corePath("error_messages.php");
        }

        return self::$messages[$rule];
    }

    protected static function getMessage($rule, $placeHolders = [])
    {
        $errorMsg = self::messages($rule);
        foreach($placeHolders as $placeHolder => $val){
            $errorMsg = str_replace($placeHolder, $val, $errorMsg);
        }

        return $errorMsg;
    }

    public static function addError($rule, $key, $placeHolders = []) 
    {
        global $errors;

        $errors[$key][$rule] = self::getMessage($rule, $placeHolders);
    }
    
    protected static function passErrorsToSession() {
        global $errors; $_SESSION['errors'] = $errors;
    }
}