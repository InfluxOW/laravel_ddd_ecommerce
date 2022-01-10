<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getResourceForm(): Form
    {
        if ($this->resourceForm === null) {
            $this->resourceForm = ProductCategoryResource::form(Form::make()->columns(2))->schema(ProductCategoryResource::getCreationFormSchema());
        }

        return $this->resourceForm;
    }

    protected function getRedirectUrl(): ?string
    {
        return static::$resource::getUrl('view', ['record' => $this->record]);
    }
}
