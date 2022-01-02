<?php

namespace App\Domain\Catalog\Providers;

use App\Domain\Catalog\Models\ProductCategory;
use App\Domain\Catalog\Observers\ProductCategoryObserver;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::CATALOG;

    protected bool $hasMigrations = true;

    protected bool $hasTranslations = true;

    protected array $providers = [
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        ProductCategory::observe(ProductCategoryObserver::class);
    }
}
