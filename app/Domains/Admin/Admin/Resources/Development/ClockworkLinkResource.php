<?php

namespace App\Domains\Admin\Admin\Resources\Development;

use App\Domains\Admin\Admin\Abstracts\SimpleResource;
use App\Domains\Admin\Admin\Resources\Development\Pages\ClockworkLink;

final class ClockworkLinkResource extends SimpleResource
{
    protected static ?string $slug = 'development/clockwork';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getPages(): array
    {
        return [
            'index' => ClockworkLink::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return config('clockwork.enable') === true;
    }
}
