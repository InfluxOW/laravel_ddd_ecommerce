<?php

namespace App\Domain\Users\Admin\Resources\UserResource\Pages;

use App\Domain\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getViewTableAction(): Action
    {
        return parent::getViewTableAction()->color('success');
    }
}
