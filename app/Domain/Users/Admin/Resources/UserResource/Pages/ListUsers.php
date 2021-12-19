<?php

namespace App\Domain\Users\Admin\Resources\UserResource\Pages;

use App\Domain\Users\Admin\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getViewLinkTableAction(): Tables\Actions\LinkAction
    {
        return parent::getViewLinkTableAction()->color('success');
    }
}
