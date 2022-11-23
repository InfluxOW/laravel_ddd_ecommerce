<?php

namespace App\Application\Exceptions;

use App\Domains\Common\Exceptions\HttpException;
use App\Interfaces\Http\Controllers\ResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(fn (NotFoundHttpException $e): mixed => $this->respondNotFound());
        $this->renderable(fn (HttpException $e): mixed => $this->respondWithMessage($e->getMessage(), $e->getCode()));
    }

    /**
     * Fallback JSON exception renderer.
     */
    protected function prepareJsonResponse($request, Throwable $e): mixed
    {
        if (app()->hasDebugModeEnabled()) {
            return parent::prepareJsonResponse($request, $e);
        }

        return $this->respondWithMessage('Sorry, something went wrong. Please, try again later!', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
