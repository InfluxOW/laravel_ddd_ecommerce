<?php

namespace App\Domains\Admin\Admin\Resources\AdminResource\Pages;

use App\Domains\Admin\Admin\Resources\AdminResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;
}
