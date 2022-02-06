<?php

namespace App\Domains\Cart\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::CART;

    protected bool $hasMigrations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
