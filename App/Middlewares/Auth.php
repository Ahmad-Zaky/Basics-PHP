<?php

namespace App\Middlewares;

class Auth
{
    public function handle() 
    {
        if (guest()) {
            redirect(route("home"));

            exit;
        }
    }
}