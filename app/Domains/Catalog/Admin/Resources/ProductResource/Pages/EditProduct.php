<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;

final class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}
