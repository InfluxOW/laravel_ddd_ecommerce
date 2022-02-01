<?php

namespace App\Domains\Admin\Providers;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Admin\Admin\Components\Widgets\CustomersChartWidget;
use App\Infrastructure\Abstracts\BaseServiceProvider;

final class DomainServiceProvider extends BaseServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::ADMIN;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;
    protected bool $hasLivewireComponents = true;

    protected array $livewireComponents = [
        CustomersChartWidget::class,
    ];
}
