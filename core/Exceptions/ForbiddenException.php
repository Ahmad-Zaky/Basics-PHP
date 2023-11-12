<?php

namespace Core\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    /**
     * code variable
     *
     * @var integer
     */
    protected $code = 403;
    
    function __construct(protected string $messsage = "")
    {
        parent::__construct();

        if (! $this->messsage) $this->message = __("You don't have permission to access this page.");
    }
}