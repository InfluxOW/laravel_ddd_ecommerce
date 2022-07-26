<?php

namespace App\Domains\Users\Admin\Resources\UserResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Users\Admin\Resources\UserResource;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
