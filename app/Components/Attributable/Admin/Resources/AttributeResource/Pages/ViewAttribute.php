<?php

namespace App\Components\Attributable\Admin\Resources\AttributeResource\Pages;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewAttribute extends ViewRecord
{
    protected static string $resource = AttributeResource::class;
}
