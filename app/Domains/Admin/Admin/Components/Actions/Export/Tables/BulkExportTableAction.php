<?php

namespace App\Domains\Admin\Admin\Components\Actions\Export\Tables;

use App\Domains\Admin\Admin\Abstracts\Actions\Tables\BulkAction;
use App\Domains\Admin\Admin\Components\Actions\Export\HasExportAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;

final class BulkExportTableAction extends BulkAction
{
    use HasExportAction;

    protected static function getActionTranslationKey(): AdminActionTranslationKey
    {
        return AdminActionTranslationKey::BULK_EXPORT;
    }
}
