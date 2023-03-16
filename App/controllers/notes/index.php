<?php

namespace App\Controllers\Notes;

use Core\DB;

global $config;

$db = app(DB::class);

$notes = $db->query("SELECT * FROM notes WHERE user_id = :user_id", [
    "user_id" => auth("id")
])->get();

view("notes.index", [
    "heading" => 'My Notes',
    "notes" => $notes
]);
