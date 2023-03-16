<?php

namespace App\Controllers\Notes;

use Core\Validator;
use Core\DB;

$rules = [
    "body" => "required|max:500|min:50"
];

if (validate($rules)) {
    $data = Validator::validated();

    $db = app(DB::class);

    $db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
        "user_id" => auth("id"),
        "body" => $data["body"],
    ]);
}

redirect("/notes");
