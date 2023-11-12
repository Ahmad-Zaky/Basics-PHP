<?php

namespace Core\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    /**
     * code variable
     *
     * @var integer
     */
    protected $code = 500;
    
    function __construct(protected string $messsage = "")
    {
        parent::__construct();

        if (! $this->messsage) $this->message = __("File does not exist");
    }
}