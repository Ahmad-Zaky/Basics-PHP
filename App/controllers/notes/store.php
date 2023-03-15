<?php

namespace App\Controllers\Notes;

use Core\Validator;
use Core\DB;

if (
    validate([
        "body" => "required|max:500|min:50"
    ])
) {
    global $auth, $config;

    $db = new DB($config["db"]);

    $data = Validator::validated();
    
    $db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
        "user_id" => $auth["id"],
        "body" => $data["body"],
    ]);
}

redirect("/notes");
