<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Catalog\Admin\Resources\ProductResource;

final class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}
