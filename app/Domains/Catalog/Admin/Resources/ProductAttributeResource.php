<?php

namespace App\Domains\Catalog\Admin\Resources;

use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminResource;
use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Enums\Translation\ProductAttributeResourceTranslationKey;
use App\Domains\Catalog\Models\ProductAttribute;
use App\Domains\Catalog\Providers\DomainServiceProvider;
use App\Domains\Components\Generic\Enums\Lang\TranslationNamespace;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductAttributeResource extends Resource
{
    use TranslatableAdminResource;
    use HasTranslatableAdminLabels;

    protected static ?string $model = ProductAttribute::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $slug = 'catalog/attributes';

    protected static ?int $navigationSort = 1;

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return route(sprintf('filament.resources.%s.view', static::$slug), ['record' => $record]);
    }

    /*
     * Data
     * */

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::setTranslatableLabels([
                Card::make()
                    ->schema(self::setTranslatableLabels([
                        TextInput::make(ProductAttributeResourceTranslationKey::TITLE->value)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, $state): mixed => $set(ProductAttributeResourceTranslationKey::SLUG->value, Str::slug($state)))
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('Width'),
                        TextInput::make(ProductAttributeResourceTranslationKey::SLUG->value)
                            ->required()
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('width'),
                        Select::make(ProductAttributeResourceTranslationKey::VALUES_TYPE->value)
                            ->required()
                            ->options(collect(ProductAttributeValuesType::cases())->reduce(fn (Collection $acc, ProductAttributeValuesType $valuesType): Collection => tap($acc, static fn () => $acc->offsetSet($valuesType->value, self::translateEnum($valuesType))), collect([])))
                            ->searchable(),
                    ]))
                    ->columnSpan(2),
                TimestampsCard::make()
                    ->columnSpan(1),
            ]))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::setTranslatableLabels([
                TextColumn::make(ProductAttributeResourceTranslationKey::TITLE->value)->sortable()->searchable(),
                TextColumn::make(ProductAttributeResourceTranslationKey::SLUG->value)->searchable(),
            ]))
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\ListProductAttributes::route('/'),
            'create' => \App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\CreateProductAttribute::route('/create'),
            'edit' => \App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\EditProductAttribute::route('/{record}/edit'),
            'view' => \App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\ViewProductAttribute::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['values']);
    }

    /*
     * Policies
     * */

    /**
     * @param ProductAttribute $record
     * @return bool
     */
    public static function canDelete(Model $record): bool
    {
        return $record->values->isEmpty();
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    /*
     * Translation
     * */

    protected static function getTranslationNamespace(): TranslationNamespace
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE;
    }

    protected static function getTranslationKeyClass(): string
    {
        return ProductAttributeResourceTranslationKey::class;
    }
}
