<?php

namespace App\Domains\Users\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;

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

        /* @phpstan-ignore-next-line  */
        SessionGuard::macro('retrieveUserByCredentials', fn (array $credentials): ?Authenticatable => $this->provider->retrieveByCredentials($credentials));
    }
}
