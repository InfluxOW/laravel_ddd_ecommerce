<?php

namespace App\Domains\Admin\Admin\Abstracts\Actions;

use App\Domains\Admin\Traits\Translation\HasTranslatableAdminActionsModals;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use Filament\Tables\Actions\Action as BaseLinkAction;

abstract class LinkAction extends BaseLinkAction
{
    use HasTranslatableAdminLabels;
    use HasTranslatableAdminActionsModals;
}
