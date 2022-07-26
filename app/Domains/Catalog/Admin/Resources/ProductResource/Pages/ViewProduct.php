<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Catalog\Admin\Resources\ProductResource;

final class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;
}
