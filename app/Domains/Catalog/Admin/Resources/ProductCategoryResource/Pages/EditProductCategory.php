<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;

final class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
