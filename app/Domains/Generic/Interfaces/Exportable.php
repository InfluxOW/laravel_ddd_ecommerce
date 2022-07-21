<?php

namespace App\Domains\Generic\Interfaces;

use App\Domains\Generic\Jobs\ExportJob;

interface Exportable
{
    /**
     * @return class-string<ExportJob>
     */
    public static function getExportJob(): string;
}
