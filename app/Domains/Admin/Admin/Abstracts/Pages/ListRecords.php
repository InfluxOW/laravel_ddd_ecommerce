<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Create\Pages\CreateAction;
use App\Domains\Admin\Admin\Components\Actions\Delete\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\DeleteBulkAction;
use App\Domains\Admin\Admin\Components\Actions\Edit\Tables\EditAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Pages\ExportAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Tables\ExportBulkAction;
use App\Domains\Admin\Admin\Components\Actions\View\Tables\ViewAction;
use App\Domains\Admin\Admin\Traits\AppliesSearchToTableQuery;
use App\Domains\Common\Interfaces\Exportable;
use Filament\Resources\Pages\ListRecords as BaseListRecords;
use Filament\Tables\Actions\DeleteBulkAction as BaseDeleteBulkAction;
use Illuminate\Database\Eloquent\Model;

abstract class ListRecords extends BaseListRecords
{
    use AppliesSearchToTableQuery;
    use ExportableResourcePage;

    protected function getActions(): array
    {
        $actions = array_merge(parent::getActions(), [
            CreateAction::create(),
        ]);
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
        $actions = [];
        $model = $this->getModel();

        foreach (parent::getTableBulkActions() as $action) {
            if ($action::class === BaseDeleteBulkAction::class) {
                continue;
            }

            $actions[] = $action;
        }

        $actions[] = DeleteBulkAction::create();

        if ($this->modelIsExportable($model)) {
            /** @var class-string<Model & Exportable> $model */
            $actions[] = ExportBulkAction::create($model);
        }

        return $actions;
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [ViewAction::create(), DeleteAction::create(), EditAction::create()]);
    }
}
