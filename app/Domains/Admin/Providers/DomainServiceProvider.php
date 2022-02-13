<?php

namespace App\Domains\Admin\Providers;

use App\Domains\Admin\Admin\Components\Widgets\CustomersChartWidget;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::ADMIN;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
    protected bool $hasLivewireComponents = true;

    protected array $livewireComponents = [
        CustomersChartWidget::class,
    ];
}
