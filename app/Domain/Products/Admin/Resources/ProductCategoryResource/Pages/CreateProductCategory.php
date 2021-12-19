<?php

namespace App\Domain\Products\Admin\Resources\ProductCategoryResource\Pages;

use App\Domain\Products\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getResourceForm(): Form
    {
        if ($this->resourceForm === null) {
            $this->resourceForm = ProductCategoryResource::form(Form::make()->columns(2))->schema(ProductCategoryResource::creationFormSchema());
        }

        return $this->resourceForm;
    }
}
