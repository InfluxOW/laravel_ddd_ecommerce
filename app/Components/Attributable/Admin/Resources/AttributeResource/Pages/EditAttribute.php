<?php

namespace App\Components\Attributable\Admin\Resources\AttributeResource\Pages;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;

final class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;
}
