<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductAttribute extends CreateRecord
{
    protected static string $resource = ProductAttributeResource::class;
}
