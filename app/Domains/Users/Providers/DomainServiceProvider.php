<?php

namespace App\Domains\Users\Providers;

use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Users\Admin\Components\Widgets\CustomersChartWidget;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::USERS;

    protected array $livewireComponents = [
        CustomersChartWidget::class,
    ];

    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
    ];
}
