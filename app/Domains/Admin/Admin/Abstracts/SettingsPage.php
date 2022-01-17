<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use Filament\Pages\SettingsPage as BaseSettingsPage;

abstract class SettingsPage extends BaseSettingsPage
{
    use TranslatableAdminResource;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}
