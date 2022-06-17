<?php

namespace App\Domains\Admin\Admin\Abstracts\Actions\Tables;

use App\Domains\Admin\Traits\Translation\HasTranslatableAdminActionsModals;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use Filament\Tables\Actions\Action as BaseAction;

abstract class Action extends BaseAction
{
    use HasTranslatableAdminLabels;
    use HasTranslatableAdminActionsModals;
}
