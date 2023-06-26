<?php

namespace Core;

class Session
{
    protected const FLASH = "flash_messages";

    function __construct()
    {
        session_start();

        $this->markFlashMessages();
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key) 
    {
        return $_SESSION[$key] ?? NULL;
    }

    public function remove($key) 
    {
        unset($_SESSION[$key]);
    }

    public function markFlashMessages()
    {
        $flashMessages = $_SESSION[self::FLASH] ?? [];
        foreach ($flashMessages as &$flashMessage) {
            $flashMessage["remove"] = true;
        }

        $_SESSION[self::FLASH] = $flashMessages;
    }

    public function setFlash($key, $message) 
    {
        $_SESSION[self::FLASH][$key] = [
            "message" => $message,
            "remove" => false
        ];
    }

    public function getFlash($key) 
    {
        return $_SESSION[self::FLASH][$key]["message"] ?? NULL;
    }

    public function destroy()
    {
        $_SESSION = [];
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
    
    public function csrfInput() 
    {
        return '<input type="hidden" name="_token" value="'. $this->csrf() .'">';
    }

    public function destroyCsrf() 
    {
        unset($_SESSION['_token']);
    }

    public function signin($user)
    {
        $_SESSION["user"] = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        ];

        session_regenerate_id();
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