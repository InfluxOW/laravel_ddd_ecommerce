<?php

namespace App\Domain\Products\Providers;

use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use App\Domain\Products\Models\ProductCategory;
use App\Domain\Products\Observers\ProductCategoryObserver;
use App\Infrastructure\Abstracts\ServiceProviderBase;

class DomainServiceProvider extends ServiceProviderBase
{
    public const TRANSLATION_NAMESPACE = TranslationNamespace::PRODUCTS;

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
