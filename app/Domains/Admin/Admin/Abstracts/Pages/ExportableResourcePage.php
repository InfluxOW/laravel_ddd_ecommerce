<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use App\Domains\Common\Interfaces\Exportable;

trait ExportableResourcePage
{
    protected function modelIsExportable(string $model): bool
    {
        $modelInterfaces = class_implements($model);

        return is_array($modelInterfaces) && isset($modelInterfaces[Exportable::class]);
    }
}
