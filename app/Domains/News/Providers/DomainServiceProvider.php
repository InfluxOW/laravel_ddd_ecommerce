<?php

namespace App\Domains\News\Providers;

use App\Components\Queryable\Abstracts\FilterBuilder;
use App\Domains\Generic\Enums\ServiceProviderNamespace;
use App\Domains\News\Services\Query\Filter\ArticleFilterBuilder;
use App\Domains\News\Services\Query\Filter\ArticleFilterService;
use App\Infrastructure\Abstracts\Providers\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::NEWS;

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    protected function afterBooting(): void
    {
        $this->app
            ->when(ArticleFilterService::class)
            ->needs(FilterBuilder::class)
            ->give(ArticleFilterBuilder::class);
    }
}
