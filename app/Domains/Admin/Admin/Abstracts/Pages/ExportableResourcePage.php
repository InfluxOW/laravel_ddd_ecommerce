<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Generic\Interfaces\Exportable;

/**
 * @internal
 * */
trait ExportableResourcePage
{
    protected function modelIsExportable(string $model): bool
    {
        $modelInterfaces = class_implements($model);

        return is_array($modelInterfaces) && isset($modelInterfaces[Exportable::class]);
    }
}
