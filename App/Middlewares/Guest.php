<?php

namespace App\Middlewares;

class Guest
{
    public function handle() 
    {
        if (auth()) {
            redirect(route("home"));

            exit;
        }
    }
}