<?php

global $auth;

$auth = $db->query("SELECT * FROM users WHERE id = :id", ["id" => 1])->findOrFail();