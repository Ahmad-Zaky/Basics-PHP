<?php

namespace Core\Middlewares;

class Guest
{
    public function handle() 
    {
        if (auth()) {
            redirect("/home");

            exit;
        }
    }
}