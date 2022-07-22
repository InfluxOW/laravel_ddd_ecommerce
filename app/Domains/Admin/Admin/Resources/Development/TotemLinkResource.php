<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\Pages\TotemLink;

final class TotemLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/totem';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getPages(): array
    {
        return [
            'index' => TotemLink::route('/'),
        ];
    }
}
