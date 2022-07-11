<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource\Pages\RabbitMQLink;

final class RabbitMQLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/rabbitmq';

    protected static ?string $navigationIcon = 'heroicon-o-switch-horizontal';

    public static function getPages(): array
    {
        return [
            'index' => RabbitMQLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return app()->isLocal();
    }
}
