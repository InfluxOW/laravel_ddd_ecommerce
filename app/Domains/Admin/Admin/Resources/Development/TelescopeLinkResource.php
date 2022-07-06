<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource\Pages\TelescopeLink;

final class TelescopeLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/telescope';

    protected static ?string $navigationIcon = 'heroicon-o-moon';

    public static function getPages(): array
    {
        return [
            'index' => TelescopeLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return config('telescope.enabled') === true;
    }
}
