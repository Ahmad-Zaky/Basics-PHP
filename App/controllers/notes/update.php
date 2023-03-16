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

    $note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => request("id")])->findOrFail();

    authorize($note["user_id"] === auth("id"));

    $db->query("UPDATE notes SET body =:body WHERE id = :id", [
        "id" => request("id"),
        "body" => $data["body"],
    ]);
}

redirect("/notes");
