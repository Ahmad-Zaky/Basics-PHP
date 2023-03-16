<?php

use Core\Validator;
use Core\DB;

$rules = [
    "name" => "required",
    "email" => "required|email|unique:users,email",
    "password" => "required|confirmed|min:6",
];

if (validate($rules)) {
    $data = Validator::validated();
    
    $db = app(DB::class);

    $db->query("INSERT INTO users(`name`, `email`, `password`) VALUES(:name, :email, :password)", [
        "name" => $data["name"],
        "email" => $data["email"],
        "password" => bcrypt($data["password"]),
    ]);

    signin($data);
}

redirect("/");
