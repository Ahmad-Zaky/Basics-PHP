<?php

use Core\Validator;
use Core\DB;

$rules = [
    "email" => "required|email",
    "password" => "required",
];

if (validate($rules)) {
    $data = Validator::validated();
    
    $db = app(DB::class);

    $user = $db->query("SELECT * FROM users WHERE email = :email", ["email" => $data["email"]])->find();

    if (verifyHash($data["password"], $user["password"])) {
        signin($user);

        redirect("/");
    }

    back([
        "errors" => ["email" => ["Email or Password doesn't exists"]]
    ]);
}

redirect("/");
