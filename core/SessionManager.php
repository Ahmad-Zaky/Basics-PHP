<?php

namespace Core;

use Core\Contracts\Authenticatable;
use Core\Contracts\Session;

class SessionManager implements Session
{
    protected const FLASH = "flash_messages";

    function __construct()
    {
        if (! isset($_SESSION)) { 
            session_start();
        }

        $this->markFlashMessages();
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? NULL;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function markFlashMessages(): void
    {
        $flashMessages = $_SESSION[self::FLASH] ?? [];
        foreach ($flashMessages as &$flashMessage) {
            $flashMessage["remove"] = true;
        }

        $_SESSION[self::FLASH] = $flashMessages;
    }

    public function setFlash(string $key, mixed $message): void
    {
        $_SESSION[self::FLASH][$key] = [
            "message" => $message,
            "remove" => false
        ];
    }

    public function getFlash($key): mixed
    {
        return $_SESSION[self::FLASH][$key]["message"] ?? NULL;
    }

    public function flush(): void
    {
        $_SESSION = [];
    }

    public function destroy(): void
    {
        $this->flush();
        session_destroy();

        $params = session_get_cookie_params();
        setcookie(
            "PHPSESSID",
            "",
            time() - 3600,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    public function csrf(): string
    {
        return $_SESSION["_token"] ?? $this->genCsrf();
    }

    public function genCsrf(): string
    {
        return $_SESSION['_token'] = bin2hex(random_bytes(40));
    }
    
    public function csrfInput(): string
    {
        return '<input type="hidden" name="_token" value="'. $this->csrf() .'">';
    }

    public function destroyCsrf(): void
    {
        unset($_SESSION['_token']);
    }

    public function signin(Authenticatable $user): void
    {
        $_SESSION["user"] = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        ];

        session_regenerate_id(true);
    }

    function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH] = $flashMessages;
    }
}