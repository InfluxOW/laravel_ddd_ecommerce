<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\Pages\PhpCacheAdminLink;

final class PhpCacheAdminLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/php_cache_admin';

    protected static ?string $navigationIcon = 'heroicon-o-server';

    public static function getPages(): array
    {
        return [
            'index' => PhpCacheAdminLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return app()->isLocal();
    }
}
