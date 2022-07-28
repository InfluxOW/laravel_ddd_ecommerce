<?php

namespace App\Domains\Admin\Admin\Resources\AdminResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Admin\Admin\Resources\AdminResource;

final class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;
}
