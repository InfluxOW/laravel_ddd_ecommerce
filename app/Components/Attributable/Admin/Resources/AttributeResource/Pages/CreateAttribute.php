<?php

namespace App\Components\Attributable\Admin\Resources\AttributeResource\Pages;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}
