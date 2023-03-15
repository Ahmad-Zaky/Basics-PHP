<?php

$heading = 'My Notes';

global $db;

$notes = $db->query("SELECT * FROM notes WHERE user_id = :user_id", [
    "user_id" => 1
])->get();

require "views". DIRECTORY_SEPARATOR ."notes". DIRECTORY_SEPARATOR ."index.view.php";