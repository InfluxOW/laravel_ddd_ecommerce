<?php

namespace App\Domains\Common\Interfaces;

use App\Domains\Common\Jobs\ExportJob;

interface Exportable
{
    /**
     * @return class-string<ExportJob>
     */
    public static function getExportJob(): string;
}
