<?php

namespace App\Components\LoginHistoryable\Providers;

use App\Domains\Common\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class ComponentServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::LOGIN_HISTORY;
}
