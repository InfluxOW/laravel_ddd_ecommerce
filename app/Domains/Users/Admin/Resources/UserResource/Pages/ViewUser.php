<?php

namespace App\Domains\Users\Admin\Resources\UserResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Users\Admin\Resources\UserResource;

final class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
