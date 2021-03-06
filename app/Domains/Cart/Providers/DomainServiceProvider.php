<?php

namespace App\Domains\Cart\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::CART;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
