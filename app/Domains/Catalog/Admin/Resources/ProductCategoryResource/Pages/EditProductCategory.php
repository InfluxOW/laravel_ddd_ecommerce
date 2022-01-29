<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\EditRecord;

final class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
