<?php

namespace App\Controllers\Notes;

use Core\DB;

global $auth, $config;

$db = new DB($config["db"]);

$note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => request("id")])->findOrFail();

authorize($note["user_id"] === $auth["id"]);

view("notes.show", [
    "heading" => 'Note',
    "note" => $note
]);
