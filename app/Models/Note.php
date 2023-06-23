<?php

namespace App\Models;

use Core\Model;

class Note extends Model
{
    protected $table = "notes";

    public static function rules(string $key = NULL)
    {
        $rules = [
            "store" => [
                "body" => "required|max:500|min:50",
                "completed" => "in:1,0",
            ],
            "update" => [
                "body" => "required|max:500|min:50",
                "completed" => "in:1,0"
            ],
        ];

        return empty($key) ? $rules : ($rules[$key] ?? []);
    }

    public static function all() 
    {
        return (new self)->query("SELECT * FROM notes WHERE user_id = :user_id", [
            "user_id" => auth("id")
        ])->get();
    }
}