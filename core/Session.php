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