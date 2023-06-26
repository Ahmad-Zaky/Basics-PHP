<?php

namespace Core;

use Core\Contracts\{Auth, Authenticatable};

class AuthenticationManager implements Auth
{
    protected string|NULL $class = '';
    
    protected Authenticatable|NULL $auth = NULL;

    function __construct()
    {
        $this->class = config("app.authenticatable");
        
        $this->user();
    }

    public function user(): mixed
    {
        if ($this->auth) return $this->auth;

        return $this->auth = ($id = session("user")["id"] ?? NULL) ? ($this->class)::find($id) : NULL;
    }
}
