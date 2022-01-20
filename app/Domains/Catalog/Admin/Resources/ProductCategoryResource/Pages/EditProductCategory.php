<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
