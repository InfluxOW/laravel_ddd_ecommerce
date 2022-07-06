<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use Filament\Resources\Resource as BaseResource;
use Illuminate\Database\Eloquent\Model;

abstract class SimpleResource extends BaseResource
{
    use TranslatableAdminResource;
    use HasNavigationSort;

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        if (static::canView($record)) {
            return static::getUrl('view', ['record' => $record]);
        }

        if (static::canEdit($record)) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return null;
    }
}
