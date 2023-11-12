<?php

namespace Core\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
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

        if (! $this->messsage) $this->message = __("Model not found.");
    }
}