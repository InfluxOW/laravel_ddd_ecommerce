<?php

namespace App\Domains\Users\Providers;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Users\Models\User;
use App\Domains\Users\Observers\UserObserver;
use App\Infrastructure\Abstracts\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::USERS;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        User::observe([UserObserver::class]);
    }
}
