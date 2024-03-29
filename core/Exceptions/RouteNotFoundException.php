<?php

namespace Core\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    /**
     * code variable
     *
     * @var integer
     */
    protected $code = 404;

    function __construct(protected string $messsage = "")
    {
        parent::__construct();

        if (! $this->messsage) $this->message = __("Page not found.");
    }
}