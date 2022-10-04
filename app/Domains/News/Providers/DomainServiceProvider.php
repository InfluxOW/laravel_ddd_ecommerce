<?php

namespace App\Domains\News\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::NEWS;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
