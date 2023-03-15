<?php

$heading = 'Note';

global $db, $auth;

$note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => $_GET["id"]])->findOrFail();

authorize($note["user_id"] === $auth["id"]);

require "views". DIRECTORY_SEPARATOR ."notes". DIRECTORY_SEPARATOR ."show.view.php";