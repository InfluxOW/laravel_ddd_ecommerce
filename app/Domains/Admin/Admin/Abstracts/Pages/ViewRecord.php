<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use Filament\Resources\Pages\ViewRecord as BaseViewRecord;

abstract class ViewRecord extends BaseViewRecord
{
    use ExportableResourceRecordPage;
}
