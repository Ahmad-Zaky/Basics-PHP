<?php

namespace Core\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = '';
    
    protected $code = 403;
    
    function __construct()
    {
        parent::__construct();

        $this->message = __("You don't have permission to access this page.");
    }
}