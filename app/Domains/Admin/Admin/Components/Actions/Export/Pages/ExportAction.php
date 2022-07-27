<?php

namespace App\Domains\Admin\Admin\Components\Actions\Export\Pages;

use App\Domains\Admin\Admin\Components\Actions\Export\HasExportAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use Filament\Pages\Actions\Action;

final class ExportAction extends Action
{
    use HasExportAction;

    protected static function getActionTranslationKey(): AdminActionTranslationKey
    {
        return AdminActionTranslationKey::EXPORT;
    }
}
