<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Models\ProductCategory;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

final class HierarchyProductCategory extends Page
{
    protected static string $resource = ProductCategoryResource::class;

    protected static string $view = 'catalog::hierarchy';

    public function getMaxDepth(): int
    {
        return ProductCategory::MAX_DEPTH + 1;
    }

    protected function getViewData(): array
    {
        return [
            'hierarchy' => ProductCategory::getHierarchy(),
        ];
    }

    public function updateHierarchy(array $updates): void
    {
        ProductCategory::$reloadHierarchyOnSavedEvent = false;

        ProductCategory::reorderHierarchy($updates);
        ProductCategory::loadHierarchy();

        ProductCategory::$reloadHierarchyOnSavedEvent = true;

        Notification::make()->title('Updated')->success()->send();
    }
}
