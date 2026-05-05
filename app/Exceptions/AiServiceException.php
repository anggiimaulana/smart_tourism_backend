<?php

namespace App\Exceptions;

use RuntimeException;

class AiServiceException extends RuntimeException
{
    public function __construct(string $message = '', private int $httpStatus = 503)
    {
        parent::__construct($message);
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
