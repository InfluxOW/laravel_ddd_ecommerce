<?php

namespace App\Domain\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domain\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProductCategory extends ViewRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
