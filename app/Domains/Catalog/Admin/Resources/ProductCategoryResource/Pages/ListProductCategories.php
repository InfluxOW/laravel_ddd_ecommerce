<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;
}
