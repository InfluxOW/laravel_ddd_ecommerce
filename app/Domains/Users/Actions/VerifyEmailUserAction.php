<?php

namespace App\Domains\Users\Actions;

use App\Domains\Generic\Enums\ConfirmationTokenType;
use App\Domains\Generic\Exceptions\InvalidConfirmationTokenException;
use App\Domains\Generic\Services\Repositories\ConfirmationTokenRepository;
use App\Domains\Users\Events\EmailVerificationSucceeded;
use App\Domains\Users\Http\Requests\EmailVerificationRequest;
use App\Domains\Users\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class VerifyEmailUserAction
{
    public function __construct(private readonly ConfirmationTokenRepository $repository)
    {
    }

    /**
     * @throws InvalidConfirmationTokenException
     * @throws NotFoundHttpException
     */
    public function execute(EmailVerificationRequest $request): void
    {
        $user = User::query()->where('email', 'ILIKE', $request->email)->first();
        if ($user === null || $user->hasVerifiedEmail()) {
            throw new NotFoundHttpException();
        }

        $this->repository->consume($user, ConfirmationTokenType::EMAIL_VERIFICATION, $request->token);

        $user->markEmailAsVerified();

        EmailVerificationSucceeded::dispatch($user);
    }
}