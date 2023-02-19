<?php

namespace App\Domains\Users\Actions;

use App\Domains\Common\Enums\ConfirmationTokenType;
use App\Domains\Common\Exceptions\InvalidConfirmationTokenException;
use App\Domains\Common\Services\Repositories\ConfirmationTokenRepository;
use App\Domains\Users\Events\EmailVerificationSucceeded;
use App\Domains\Users\Http\Requests\EmailVerificationRequest;
use App\Domains\Users\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class VerifyEmailUserAction
{
    public function __construct(private ConfirmationTokenRepository $repository)
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
