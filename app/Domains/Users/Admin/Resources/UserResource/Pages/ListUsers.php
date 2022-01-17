<?php

namespace App\Domains\Users\Admin\Resources\UserResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Users\Admin\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
