<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use Filament\Resources\Pages\EditRecord;

final class EditProductAttribute extends EditRecord
{
    protected static string $resource = ProductAttributeResource::class;
}
