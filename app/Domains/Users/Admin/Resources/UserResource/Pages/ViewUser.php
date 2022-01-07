<?php

namespace App\Domains\Users\Admin\Resources\UserResource\Pages;

use App\Domains\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
