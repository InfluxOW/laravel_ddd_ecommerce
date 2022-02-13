<?php

namespace App\Domains\Catalog\Providers;

use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Observers\ProductCategoryObserver;
use App\Domains\Catalog\Observers\ProductObserver;
use App\Domains\Catalog\Observers\ProductProductCategoryObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

final class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        ProductCategory::observe([ProductCategoryObserver::class]);
        Product::observe([ProductObserver::class]);
        ProductProductCategory::observe([ProductProductCategoryObserver::class]);
    }
}
