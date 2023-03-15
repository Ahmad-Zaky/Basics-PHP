<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    showErrors();

    if (
        validate([
            "body" => "required|max:500|min:50"
        ])
    ) {
        global $db, $auth;

        $data = Validator::validated();
        
        $db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
            "user_id" => $auth["id"],
            "body" => $data["body"],
        ]);
    }
}

redirect("/notes");
