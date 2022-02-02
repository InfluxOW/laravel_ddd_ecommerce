<?php

namespace App\Domains\Catalog\Providers;

use App\Components\Generic\Enums\ServiceProviderNamespace;
use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Observers\ProductCategoryObserver;
use App\Domains\Catalog\Observers\ProductObserver;
use App\Domains\Catalog\Observers\ProductProductCategoryObserver;
use App\Infrastructure\Abstracts\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public const NAMESPACE = ServiceProviderNamespace::CATALOG;

    protected bool $hasMigrations = true;
    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        ProductCategory::observe([ProductCategoryObserver::class]);
        Product::observe([ProductObserver::class]);
        ProductProductCategory::observe([ProductProductCategoryObserver::class]);
    }
}
