<?php

namespace App\Domains\Users\Listeners;

use App\Domains\Generic\Enums\ConfirmationTokenType;
use App\Domains\Generic\Services\Repositories\ConfirmationTokenRepository;
use App\Domains\Users\Events\EmailVerificationFailed;
use App\Domains\Users\Events\Registered;
use App\Domains\Users\Models\User;
use App\Domains\Users\Notifications\EmailVerificationNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

final class SendEmailVerificationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(private ConfirmationTokenRepository $repository)
    {
        //
    }

    public function handle(Registered|EmailVerificationFailed $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $token = $this->repository->create(ConfirmationTokenType::EMAIL_VERIFICATION, $user, Carbon::now()->addHour());

        Notification::send($user, new EmailVerificationNotification($token));
    }
}
