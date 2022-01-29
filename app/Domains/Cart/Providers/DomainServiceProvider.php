<?php

namespace App\Domains\Cart\Providers;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

final class DomainServiceProvider extends ServiceProviderBase
{
    public const NAMESPACE = ServiceProviderNamespace::CART;

    protected bool $hasMigrations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
