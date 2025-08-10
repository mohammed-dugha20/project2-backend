<?php

namespace App\Exceptions;

use Exception;

class RealEstateOfficeException extends Exception
{
    public function __construct(string $message = 'Real estate office operation failed', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 