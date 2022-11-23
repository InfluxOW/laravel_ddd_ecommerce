<?php

namespace App\Domains\Cart\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::CART;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
