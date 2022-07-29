<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use Filament\Resources\Form;
use Filament\Resources\Pages\EditRecord as BaseEditRecord;

abstract class EditRecord extends BaseEditRecord
{
    use ResourceRecordPage;

    protected function getResourceForm(?int $columns = null, bool $isDisabled = false): Form
    {
        return static::$resource::editingForm($this->getBaseResourceForm($columns, $isDisabled));
    }
}
