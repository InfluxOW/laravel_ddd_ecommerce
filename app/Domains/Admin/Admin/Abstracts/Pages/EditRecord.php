<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use Filament\Resources\Pages\EditRecord as BaseEditRecord;

abstract class EditRecord extends BaseEditRecord
{
    use ExportableResourceRecordPage;
}
