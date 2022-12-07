<?php

namespace App\Domains\Catalog\Admin\Resources\ProductCategoryResource\RelationManagers;

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Enums\Translation\ProductCategoryTranslationKey;
use App\Domains\Catalog\Models\ProductCategory;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

final class ProductCategoryChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(ProductCategoryResource::editingForm($form)->getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(ProductCategoryTranslationKey::TITLE)->searchable(),
                TextColumn::makeTranslated(ProductCategoryTranslationKey::SLUG)->searchable(),
            ]);
    }

    /*
     * Policies
     * */

    public function canCreate(): bool
    {
        /** @var ProductCategory $category */
        $category = $this->ownerRecord;

        return parent::canCreate() && $category->depth < ProductCategory::MAX_DEPTH;
    }

    /**
     * @param ProductCategory $record
     */
    public function canDelete(Model $record): bool
    {
        return parent::canDelete($record) && ProductCategoryResource::canDelete($record);
    }

    protected function canDeleteAny(): bool
    {
        return parent::canDeleteAny() && ProductCategoryResource::canDeleteAny();
    }
}
