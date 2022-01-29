<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewProductCategory extends ViewRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
