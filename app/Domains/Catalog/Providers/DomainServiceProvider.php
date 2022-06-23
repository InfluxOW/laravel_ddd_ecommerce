<?php

namespace App\Domains\Catalog\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::CATALOG;

    protected bool $hasMigrations = true;

    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];
}
