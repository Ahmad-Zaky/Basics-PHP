<?php

namespace Core\Middlewares;

class Auth
{
    public function handle() 
    {
        if (! auth()) {
            redirect("/home");

            exit;
        }
    }
}