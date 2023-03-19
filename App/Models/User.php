<?php

namespace App\Models;

use Core\DB;
use Core\Model;

class User extends Model
{
    protected $table = "users";

    public static $rules = [
        "signin" => [
            "email" => "required|email",
            "password" => "required",
        ],
        "signup" => [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed|min:6",
        ],
    ];

    public static function findByEmail($email) 
    {
        $db = app(DB::class);

        return $db->query("SELECT * FROM users WHERE email = :email", ["email" => $email])->find();
    }
}