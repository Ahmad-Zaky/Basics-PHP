<?php

namespace Core\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message = "";

    protected $code = 404;

    function __construct()
    {
        parent::__construct();

        $this->message = __("Page not found.");
    }
}