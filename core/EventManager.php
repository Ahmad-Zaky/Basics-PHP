<?php

namespace Core;

use Closure;
use Core\Contracts\Event;

class EventManager implements Event
{
    public static $events = [];

    public static function trigger(string $event, array $args = []): void
    {
        if(isset(self::$events[$event]))
        {
            foreach(self::$events[$event] as $func)
            {
                call_user_func_array($func, $args);
            }
        }
    }

    public static function bind(string $event, Closure $func): void
    {
        self::$events[$event][] = $func;
    }
}