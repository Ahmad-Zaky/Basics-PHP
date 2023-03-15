<?php

namespace App\Controllers\Notes;

use Core\DB;

$db = new DB(config()["db"]);

$note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => request("id")])->findOrFail();

authorize($note["user_id"] === auth()["id"]);

$db->query("DELETE FROM notes WHERE id = :id", [
    "id" => request("id"),
]);

redirect("/notes");
