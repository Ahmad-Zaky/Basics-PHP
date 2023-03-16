<?php

namespace App\Controllers\Notes;

use Core\DB;

$db = app(DB::class);

$note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => request("id")])->findOrFail();

authorize($note["user_id"] === auth("id"));

view("notes.edit", [
    "heading" => 'Edit Note',
    "note" => $note
]);
