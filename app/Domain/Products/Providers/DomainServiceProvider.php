<?php

namespace App\Domain\Products\Providers;

use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const ALIAS = 'products';

    protected bool $hasMigrations = true;

    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
