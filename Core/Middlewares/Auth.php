<?php

namespace Core\Middlewares;

class Auth
{
    public function handle() 
    {
        if (! auth()) {
            redirect(route("home"));

            exit;
        }
    }
}