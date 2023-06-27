<?php

namespace Core;

use Core\Contracts\{Auth, Authenticatable, Config, Session};

class AuthenticationManager implements Auth
{
    protected ?string $class = '';
    
    protected ?string $column = '';
    
    protected Authenticatable|NULL $auth = NULL;

    function __construct(Config $config, protected Session $session)
    {
        $this->class = $config->get("app.authenticatable");
        $this->column = $config->get("app.authenticatable_col");

        $this->user();
    }

    public function user(): mixed
    {
        if ($this->auth) return $this->auth;

        return $this->auth = ($id = $this->session->get("user")["id"] ?? NULL) ? ($this->class)::find($id) : NULL;
    }

    public function attempt(array $credentials): bool
    {
        if (! $password = $credentials["password"] ?? NULL) {
            return false;
        }
        
        if (! ($credentials[$this->column] ?? false)) {
            return false;
        }

        $columnValue = $credentials[$this->column];
        $user = (new $this->class)
            ->query("SELECT * FROM users WHERE {$this->column} = :column", [
                "column" => $columnValue
            ])->first();

        if ($user && verifyHash($password, $user->password)) {
            signin($user);

            return true;
        }

        return false;
    }
}
