<?php

namespace App\Domains\Common\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class HttpException extends Exception
{
    public function __construct(string $message = '', int $code = Response::HTTP_BAD_REQUEST, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
