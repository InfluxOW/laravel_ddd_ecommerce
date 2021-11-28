<?php

namespace App\Domain\Users\Providers;

use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const ALIAS = 'users';

    protected bool $hasMigrations = true;

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];
}
