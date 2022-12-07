<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Catalog\Admin\Components\Actions\HierarchyAction;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;

final class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getActions(): array
    {
        return array_merge([
            HierarchyAction::create(),
        ], parent::getActions());
    }
}
