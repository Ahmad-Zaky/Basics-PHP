<?php

namespace App\Controllers\Notes;

use Core\DB;

global $config;

$db = new DB($config["db"]);

$notes = $db->query("SELECT * FROM notes WHERE user_id = :user_id", [
    "user_id" => 1
])->get();

view("notes.index", [
    "heading" => 'My Notes',
    "notes" => $notes
]);
