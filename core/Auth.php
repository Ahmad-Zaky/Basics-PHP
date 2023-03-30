<?php

namespace Core;

use App\Models\User;

class Auth
{
    protected $auth = NULL;

    function __construct()
    {
        $this->user();
    }

    public function user() 
    {
        if ($this->auth) return $this->auth;

        return $this->auth = ($id = session("user")["id"] ?? NULL) ? User::find($id) : NULL;
    }
}
