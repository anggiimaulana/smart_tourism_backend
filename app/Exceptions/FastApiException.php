<?php

namespace App\Exceptions;

use RuntimeException;

class FastApiException extends RuntimeException
{
    public function __construct(string $message = '', private int $httpStatus = 502)
    {
        parent::__construct($message);
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
