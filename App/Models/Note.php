<?php

namespace App\Models;

use Core\DB;
use Core\Model;

class Note extends Model
{
    protected $table = "notes";

    public static $rules = [
        "store" => [
            "body" => "required|max:500|min:50"
        ],
        "update" => [
            "body" => "required|max:500|min:50"
        ],
    ];

    public static function all() 
    {
        $db = app(DB::class);

        return $db->query("SELECT * FROM notes WHERE user_id = :user_id", [
            "user_id" => auth("id")
        ])->get();
    }

}