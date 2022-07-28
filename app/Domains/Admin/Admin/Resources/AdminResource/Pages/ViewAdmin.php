<?php

namespace App\Domains\Admin\Admin\Resources\AdminResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Admin\Admin\Resources\AdminResource;

final class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;
}
