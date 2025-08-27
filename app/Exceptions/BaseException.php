<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    protected $statusCode = 500;
    protected $errorCode = 'GENERIC_ERROR';

    public function __construct(string $message = '', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get application error code
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Convert exception to array for API response
     */
    public function toArray(): array
    {
        return [
            'error_code' => $this->getErrorCode(),
            'message' => $this->getMessage(),
            'status_code' => $this->getStatusCode(),
        ];
    }
}