<?php

namespace App\Domains\Admin\Admin\Components\Actions\Export\Tables;

use App\Domains\Admin\Admin\Abstracts\Actions\Tables\Action;
use App\Domains\Admin\Admin\Components\Actions\Export\HasExportAction;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Generic\Interfaces\Exportable;
use Illuminate\Database\Eloquent\Model;

final class ExportTableAction extends Action
{
    use HasExportAction {
        create as baseCreate;
    }

    /**
     * @param class-string<Model & Exportable> $model
     */
    public static function create(string $model): self
    {
        return self::baseCreate($model)->button();
    }

    protected static function getActionTranslationKey(): AdminActionTranslationKey
    {
        return AdminActionTranslationKey::EXPORT;
    }
}
