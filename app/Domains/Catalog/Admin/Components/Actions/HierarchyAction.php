<?php

namespace App\Domains\Catalog\Admin\Components\Actions;

use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Enums\Translation\AdminActionTranslationKey;
use Filament\Pages\Actions\ViewAction;

final class HierarchyAction extends ViewAction
{
    public static function create(): ViewAction
    {
        return ViewAction::makeTranslated(AdminActionTranslationKey::HIERARCHY)
            ->url(ProductCategoryResource::getUrl('hierarchy'))
            ->icon('heroicon-o-collection');
    }
}
