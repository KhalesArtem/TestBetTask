<?php

namespace App\Exceptions;

use Exception;

class LinkNotAccessibleException extends Exception
{
    public function __construct(?string $message = 'Link is not accessible')
    {
        parent::__construct($message ?? 'Link is not accessible');
    }
}