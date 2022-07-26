<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;

final class ViewProductCategory extends ViewRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
