<?php

namespace App\Domains\Users\Providers;

use App\Domains\Users\Events\EmailVerificationFailed;
use App\Domains\Users\Events\Registered;
use App\Domains\Users\Listeners\SendEmailVerificationNotification;
use App\Domains\Users\Models\User;
use App\Domains\Users\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

final class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $listen = [
        EmailVerificationFailed::class => [
            SendEmailVerificationNotification::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe([UserObserver::class]);
    }
}
