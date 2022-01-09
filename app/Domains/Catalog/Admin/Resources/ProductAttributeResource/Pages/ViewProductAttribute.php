<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProductAttribute extends ViewRecord
{
    protected static string $resource = ProductAttributeResource::class;
}
