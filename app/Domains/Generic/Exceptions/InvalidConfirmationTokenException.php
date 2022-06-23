<?php

namespace App\Domains\Generic\Exceptions;

use App\Domains\Generic\Models\ConfirmationToken;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class InvalidConfirmationTokenException extends Exception
{
    public function __construct(public ?ConfirmationToken $token, string $message = '', int $code = Response::HTTP_BAD_REQUEST, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
