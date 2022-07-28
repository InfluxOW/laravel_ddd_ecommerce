<?php

namespace App\Domains\Admin\Admin\Resources\AdminResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Admin\Admin\Resources\AdminResource;

final class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;
}
