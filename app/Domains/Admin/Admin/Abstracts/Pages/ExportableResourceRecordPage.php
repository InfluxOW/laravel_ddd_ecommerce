<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Admin\Admin\Components\Actions\Export\Pages\ExportAction;
use App\Domains\Generic\Interfaces\Exportable;
use Illuminate\Database\Eloquent\Model;

/**
 * @internal
 * */
trait ExportableResourceRecordPage
{
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
}
