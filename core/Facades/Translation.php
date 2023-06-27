<?php

namespace Core\Facades;

class Translation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'trans';
    }
}