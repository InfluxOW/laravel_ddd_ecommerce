<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateProductAttribute extends CreateRecord
{
    protected static string $resource = ProductAttributeResource::class;
}
