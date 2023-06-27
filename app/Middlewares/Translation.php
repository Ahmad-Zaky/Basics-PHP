<?php

namespace App\Middlewares;

use Exception;

class Translation
{
    public function handle() 
    {
        if (
            request('lang') &&
            ! in_array(request('lang'), config('app.locals'))
        ) {
            throw new Exception(__("Unknown language."));
        }

        app()->setLocal(request('lang'));
    } 
}