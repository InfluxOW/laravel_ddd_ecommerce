<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Delete\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Edit\Tables\EditAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Pages\ExportAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Tables\BulkExportAction;
use App\Domains\Admin\Admin\Components\Actions\View\Tables\ViewAction;
use App\Domains\Admin\Admin\Traits\AppliesSearchToTableQuery;
use App\Domains\Generic\Interfaces\Exportable;
use Filament\Resources\Pages\ListRecords as BaseListRecords;
use Illuminate\Database\Eloquent\Model;

abstract class ListRecords extends BaseListRecords
{
    use AppliesSearchToTableQuery;
    use ExportableResourcePage;

    protected function getActions(): array
    {
        $actions = parent::getActions();
        $model = $this->getModel();

        if ($this->modelIsExportable($model)) {
            /** @var class-string<Model & Exportable> $model */
            $actions = array_merge($actions, [
                ExportAction::create($model),
            ]);
        }

        return $actions;
    }

    protected function getTableBulkActions(): array
    {
        $actions = parent::getTableBulkActions();
        $model = $this->getModel();

        if ($this->modelIsExportable($model)) {
            /** @var class-string<Model & Exportable> $model */
            $actions = array_merge($actions, [
                BulkExportAction::create($model),
            ]);
        }

        return $actions;
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [ViewAction::create(), DeleteAction::create(), EditAction::create()]);
    }
}
