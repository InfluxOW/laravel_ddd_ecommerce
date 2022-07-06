<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource\Pages\HorizonLink;

final class HorizonLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/horizon';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    public static function getPages(): array
    {
        return [
            'index' => HorizonLink::route('/'),
        ];
    }
}
