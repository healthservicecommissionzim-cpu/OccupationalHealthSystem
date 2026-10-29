<?php

namespace App\Exceptions;

use Exception;

class OverlapException extends Exception
{
    /**
     * Create a new overlap exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        string $message = "Assignment dates overlap with existing assignment",
        int $code = 422,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
