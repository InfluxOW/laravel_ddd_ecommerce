<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;

abstract class Resource extends SimpleResource
{
    use HasTranslatableAdminLabels;
}
