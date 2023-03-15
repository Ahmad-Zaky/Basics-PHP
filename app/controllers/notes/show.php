<?php

global $db, $auth;

$note = $db->query("SELECT * FROM notes WHERE id = :id", ["id" => $_GET["id"]])->findOrFail();

authorize($note["user_id"] === $auth["id"]);

view("notes.show", [
    "heading" => 'Note',
    "note" => $note
]);
