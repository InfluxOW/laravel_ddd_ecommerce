<?php

namespace App\Domains\Catalog\Admin\Resources\ProductResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): ?string
    {
        return static::$resource::getUrl('view', ['record' => $this->record]);
    }
}
