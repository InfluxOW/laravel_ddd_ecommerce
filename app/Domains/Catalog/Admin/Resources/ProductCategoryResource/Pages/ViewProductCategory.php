<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProductCategory extends ViewRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
