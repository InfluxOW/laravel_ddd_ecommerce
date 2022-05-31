<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;

final class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getResourceForm(?int $columns = null, bool $isDisabled = false): Form
    {
        return ProductCategoryResource::form(Form::make()->columns($columns))->schema(ProductCategoryResource::getCreationFormSchema());
    }
}
