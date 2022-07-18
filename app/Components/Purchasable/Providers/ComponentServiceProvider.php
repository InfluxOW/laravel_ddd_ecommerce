<?php

namespace App\Components\Purchasable\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class ComponentServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::PURCHASE;
}
