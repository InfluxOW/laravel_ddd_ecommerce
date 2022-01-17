<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use Filament\Resources\Resource as BaseResource;

abstract class Resource extends BaseResource
{
    use TranslatableAdminResource;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}
