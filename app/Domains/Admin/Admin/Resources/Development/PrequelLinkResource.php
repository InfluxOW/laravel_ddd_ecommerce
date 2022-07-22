<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\Pages\PrequelLink;

final class PrequelLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/prequel';

    protected static ?string $navigationIcon = 'heroicon-o-database';

    public static function getPages(): array
    {
        return [
            'index' => PrequelLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (app()->isProduction()) {
            return false;
        }

        return config('prequel.enabled') === true;
    }
}
