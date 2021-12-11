<?php

namespace App\Domain\Users\Admin\Resources\UserResource\Pages;

use App\Domain\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
