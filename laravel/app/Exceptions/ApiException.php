<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ApiException extends Exception
{
    public array $errors;

    public function __construct(
        string $message = 'API error',
        int $code = Response::HTTP_BAD_REQUEST,
        array $errors = []
    ) {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }
}
