<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages;

use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;
}
