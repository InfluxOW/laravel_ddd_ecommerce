<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Export\Tables\BulkExportTableAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Tables\ExportTableAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ViewAction;
use App\Domains\Admin\Admin\Traits\AppliesSearchToTableQuery;
use App\Domains\Generic\Interfaces\Exportable;
use Filament\Resources\Pages\ListRecords as BaseListRecords;
use Illuminate\Database\Eloquent\Model;

abstract class ListRecords extends BaseListRecords
{
    use AppliesSearchToTableQuery;
    use ExportableResourcePage;

    protected function getTableHeaderActions(): array
    {
        $actions = parent::getTableHeaderActions();
        $model = $this->getModel();

        if ($this->modelIsExportable($model)) {
            /** @var class-string<Model & Exportable> $model */
            $actions = array_merge($actions, [
                ExportTableAction::create($model),
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
                BulkExportTableAction::create($model),
            ]);
        }

        return $actions;
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [ViewAction::create(), DeleteAction::create()]);
    }
}
