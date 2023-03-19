<?php

namespace Core\Middlewares;

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