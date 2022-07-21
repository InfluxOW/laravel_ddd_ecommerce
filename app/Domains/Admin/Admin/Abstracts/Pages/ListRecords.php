<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ExportAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ViewAction;
use App\Domains\Admin\Admin\Traits\AppliesSearchToTableQuery;
use App\Domains\Generic\Interfaces\Exportable;
use Filament\Resources\Pages\ListRecords as BaseListRecords;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;

abstract class ListRecords extends BaseListRecords
{
    use AppliesSearchToTableQuery;

    protected function getTableHeaderActions(): array
    {
        $actions = parent::getTableHeaderActions();
        /** @var FilamentResource $resource */
        $resource = static::getResource();
        $model = $resource::getModel();
        $modelInterfaces = class_implements($model);

        if (is_array($modelInterfaces) && isset($modelInterfaces[Exportable::class])) {
            /** @var class-string<Model & Exportable> $model */
            $actions = array_merge($actions, [
                ExportAction::create($model),
            ]);
        }

        return $actions;
    }

    protected function getTableActions(): array
    {
        return array_merge(parent::getTableActions(), [ViewAction::create(), DeleteAction::create()]);
    }
}
