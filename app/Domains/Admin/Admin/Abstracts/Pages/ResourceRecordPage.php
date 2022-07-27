<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Delete\Pages\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Edit\Pages\EditAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Pages\ExportAction;
use App\Domains\Admin\Admin\Components\Actions\View\Pages\ViewAction;
use App\Domains\Generic\Interfaces\Exportable;
use Illuminate\Database\Eloquent\Model;

/**
 * @internal
 * */
trait ResourceRecordPage
{
    use ExportableResourcePage;

    protected function getActions(): array
    {
        $actions = array_merge(parent::getActions(), [
            ViewAction::create(),
            DeleteAction::create(),
            EditAction::create(),
        ]);
        $model = $this->getModel();

        if ($this->modelIsExportable($model)) {
            /** @var class-string<Model & Exportable> $model */
            $actions[] = ExportAction::create($model);
        }

        return $actions;
    }
}
