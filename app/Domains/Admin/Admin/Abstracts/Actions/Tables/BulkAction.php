<?php

namespace App\Domains\Admin\Admin\Abstracts\Actions\Tables;

use App\Domains\Admin\Traits\Translation\HasTranslatableAdminActionsModals;
use Filament\Tables\Actions\BulkAction as BaseBulkAction;

abstract class BulkAction extends BaseBulkAction
{
    use HasTranslatableAdminActionsModals;
}
