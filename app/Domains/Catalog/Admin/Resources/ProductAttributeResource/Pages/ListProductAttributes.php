<?php

namespace App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource;

class ListProductAttributes extends ListRecords
{
    protected static string $resource = ProductAttributeResource::class;
}
