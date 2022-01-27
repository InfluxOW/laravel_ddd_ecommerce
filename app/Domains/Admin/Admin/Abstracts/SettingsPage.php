<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminPage;
use Filament\Pages\SettingsPage as BaseSettingsPage;

abstract class SettingsPage extends BaseSettingsPage
{
    use TranslatableAdminPage;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;
}
