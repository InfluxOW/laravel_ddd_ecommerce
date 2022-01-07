<?php

namespace App\Domains\Users\Admin\Resources\UserResource\Pages;

use App\Domains\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
