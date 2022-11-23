<?php

namespace App\Domains\News\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::NEWS;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
