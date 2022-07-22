<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\Pages\SwaggerLink;

final class SwaggerLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/swagger';

    protected static ?string $navigationIcon = 'heroicon-o-document-search';

    public static function getPages(): array
    {
        return [
            'index' => SwaggerLink::route('/'),
        ];
    }
}
