<?php

namespace App\Domains\Users\Notifications;

use App\Domains\Common\Models\ConfirmationToken;
use App\Domains\Users\Models\User;
use App\Infrastructure\Abstracts\Notifications\EmailNotification;
use Illuminate\Notifications\Messages\MailMessage;

final class EmailVerificationNotification extends EmailNotification
{
    public function __construct(private ConfirmationToken $token)
    {
        parent::__construct();
    }

    /**
     * @param User $notifiable
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $appName = config('app.name');

        return (new MailMessage())
            ->success()
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("{$appName} - Confirm your registration")
            ->greeting("Welcome to {$appName}!")
            ->lines([
                'Use code below to verify your email',
                $this->getTokenString(),
            ]);

        /*
         * You can also create web page that will automatically call the API to verify an email
         * and use its url here like this.
         *
         * ->action('Verify Email', route('web.users.verify.email', ['email' => $notifiable->email, 'token' => $this->getTokenString()]))
         * */
    }

    public function getToken(): ConfirmationToken
    {
        return $this->token;
    }

    public function getTokenString(): string
    {
        return $this->token->token;
    }
}
