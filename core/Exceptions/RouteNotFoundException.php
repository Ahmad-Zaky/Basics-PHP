<?php

namespace Core\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message = "Page not found.";

    protected $code = 404;
}