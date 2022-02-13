<?php

namespace App\Components\Addressable\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class ComponentServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::ADDRESS;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
}
