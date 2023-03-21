<?php

namespace Core\Middlewares;

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