<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource\Pages\ElasticvueLink;

final class ElasticvueLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/elasticvue';

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    public static function getPages(): array
    {
        return [
            'index' => ElasticvueLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return app()->isLocal();
    }
}
