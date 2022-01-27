<?php

namespace App\Domains\Admin\Admin\Abstracts\Actions;

use App\Domains\Admin\Traits\Translation\HasTranslatableAdminActionsModals;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use Filament\Tables\Actions\BulkAction as BaseBulkAction;

abstract class BulkAction extends BaseBulkAction
{
    use HasTranslatableAdminLabels;
    use HasTranslatableAdminActionsModals;
}
