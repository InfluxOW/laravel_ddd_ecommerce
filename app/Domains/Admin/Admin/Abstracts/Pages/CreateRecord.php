<?php

namespace App\Domains\Admin\Admin\Abstracts\Pages;

use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord as BaseCreateRecord;

abstract class CreateRecord extends BaseCreateRecord
{
    protected function getResourceForm(?int $columns = null, bool $isDisabled = false): Form
    {
        return static::$resource::creationForm($this->getBaseResourceForm($columns, $isDisabled));
    }
}
