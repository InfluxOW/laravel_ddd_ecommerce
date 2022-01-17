<?php

namespace App\Domains\Admin\Traits;

use App\Domains\Admin\Helpers\AdminNavigationSortHelper;

trait HasNavigationSort
{
    protected static function getNavigationSort(): ?int
    {
        /** @phpstan-ignore-next-line */
        return array_flip(AdminNavigationSortHelper::NAVIGATION_SORT_BY_CLASS)[static::class] ?? null;
    }
}
