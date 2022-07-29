<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord as BaseViewRecord;

abstract class ViewRecord extends BaseViewRecord
{
    use ResourceRecordPage;

    protected function getResourceForm(?int $columns = null, bool $isDisabled = false): Form
    {
        return static::$resource::viewingForm($this->getBaseResourceForm($columns, $isDisabled));
    }
}
