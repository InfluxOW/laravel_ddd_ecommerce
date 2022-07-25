<?php

namespace App\Components\Attributable\Admin\Resources\AttributeResource\Pages;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;

final class ListAttributes extends ListRecords
{
    protected static string $resource = AttributeResource::class;
}
