<?php

namespace App\Components\Attributable\Admin\Resources\AttributeResource\Pages;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use Filament\Resources\Pages\EditRecord;

final class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;
}
