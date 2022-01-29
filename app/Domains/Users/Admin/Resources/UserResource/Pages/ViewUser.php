<?php

namespace App\Domains\Users\Admin\Resources\UserResource\Pages;

use App\Domains\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
