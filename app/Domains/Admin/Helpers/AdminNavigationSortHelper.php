<?php

namespace App\Domains\Admin\Helpers;

use App\Domains\Catalog\Admin\Pages\ManageCatalogSettings;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Users\Admin\Resources\UserResource;

class AdminNavigationSortHelper
{
    public const NAVIGATION_SORT_BY_CLASS = [
        ManageCatalogSettings::class,
        UserResource::class,
        ProductAttributeResource::class,
        ProductCategoryResource::class,
        ProductResource::class,
    ];
}
