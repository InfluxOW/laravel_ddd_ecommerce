<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\Pages\KibanaLink;

final class KibanaLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/kibana';

    protected static ?string $navigationIcon = 'heroicon-o-cloud';

    public static function getPages(): array
    {
        return [
            'index' => KibanaLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return app()->isLocal();
    }
}
