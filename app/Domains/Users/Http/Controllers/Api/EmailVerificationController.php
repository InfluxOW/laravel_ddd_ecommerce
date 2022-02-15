<?php

namespace App\Domains\Users\Http\Controllers\Api;

use App\Domains\Generic\Enums\ConfirmationTokenType;
use App\Domains\Generic\Exceptions\InvalidConfirmationTokenException;
use App\Domains\Generic\Services\Repositories\ConfirmationTokenRepository;
use App\Domains\Users\Events\EmailVerificationSucceeded;
use App\Domains\Users\Http\Requests\EmailVerificationRequest;
use App\Domains\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class EmailVerificationController extends Controller
{
    public function __invoke(EmailVerificationRequest $request, ConfirmationTokenRepository $repository): JsonResponse
    {
        $user = User::query()->where('email', 'ILIKE', $request->email)->first();
        if ($user === null || $user->hasVerifiedEmail()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        try {
            $repository->consume($user, ConfirmationTokenType::EMAIL_VERIFICATION, $request->token);
        } catch (InvalidConfirmationTokenException) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $user->markEmailAsVerified();

        EmailVerificationSucceeded::dispatch($user);

        return $this->respondSuccess();
    }
}
