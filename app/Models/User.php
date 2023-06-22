<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = "users";

    public static function rules(string $key = NULL)
    {
        $rules = [
            "signin" => [
                "email" => "required|email",
                "password" => "required",
            ],
            "signup" => [
                "name" => "required",
                "email" => ["required", "email", "unique:users,email", function ($attribute, $value, $fail) {
                    $email = explode('@', $value);
                    if (strpos(($email[1] ?? ""), "gmail.com") === false) {
                        $fail("Email should be a gmail email address.");
                    }
                }],
                "password" => "required|confirmed|min:6",
            ],
        ];

        return empty($key) ? $rules : ($rules[$key] ?? []);
    }

    public static function findByEmail($email) 
    {
        return (new self)->query("SELECT * FROM users WHERE email = :email", ["email" => $email])->first();
    }

    public static function findOrFailByEmail($email) 
    {
        return (new self)->query("SELECT * FROM users WHERE email = :email", ["email" => $email])->firstOrFail();
    }
}