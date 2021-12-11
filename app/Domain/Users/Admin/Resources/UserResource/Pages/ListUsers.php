<?php

namespace App\Domain\Users\Admin\Resources\UserResource\Pages;

use App\Domain\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
