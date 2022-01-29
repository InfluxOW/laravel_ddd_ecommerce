<?php

namespace App\Components\Addressable\Providers;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

final class ComponentServiceProvider extends ServiceProviderBase
{
    public const NAMESPACE = ServiceProviderNamespace::ADDRESS;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
}
