<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;
}
