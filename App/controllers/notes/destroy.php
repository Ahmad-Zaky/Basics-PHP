<?php

namespace App\Controllers\Notes;

use Core\App;
use Core\DB;


$db = app(DB::class);

$note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => request("id")])->findOrFail();

authorize($note["user_id"] === auth()["id"]);

$db->query("DELETE FROM notes WHERE id = :id", [
    "id" => request("id"),
]);

redirect("/notes");
