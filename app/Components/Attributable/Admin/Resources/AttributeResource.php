<?php

namespace App\Components\Attributable\Admin\Resources;

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Enums\Translation\AttributeTranslationKey;
use App\Components\Attributable\Models\Attribute;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Generic\Utils\LangUtils;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'attributes';

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    /*
     * Global Search
     * */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    /*
     * Data
     * */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::makeTranslated(AttributeTranslationKey::TITLE)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set, $state): mixed => $set(AttributeTranslationKey::SLUG->value, Str::slug($state)))
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('Width'),
                        TextInput::makeTranslated(AttributeTranslationKey::SLUG)
                            ->required()
                            ->minValue(2)
                            ->maxLength(255)
                            ->placeholder('width'),
                        Select::makeTranslated(AttributeTranslationKey::VALUES_TYPE)
                            ->required()
                            ->options(collect(AttributeValuesType::cases())->reduce(fn (Collection $acc, AttributeValuesType $valuesType): Collection => tap($acc, static fn () => $acc->offsetSet($valuesType->value, LangUtils::translateEnum($valuesType))), collect([])))
                            ->searchable(),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::makeTranslated(AttributeTranslationKey::TITLE)->sortable()->searchable(),
                TextColumn::makeTranslated(AttributeTranslationKey::SLUG)->searchable(),
            ])
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
            'index' => \App\Components\Attributable\Admin\Resources\AttributeResource\Pages\ListAttributes::route('/'),
            'create' => \App\Components\Attributable\Admin\Resources\AttributeResource\Pages\CreateAttribute::route('/create'),
            'edit' => \App\Components\Attributable\Admin\Resources\AttributeResource\Pages\EditAttribute::route('/{record}/edit'),
            'view' => \App\Components\Attributable\Admin\Resources\AttributeResource\Pages\ViewAttribute::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount(['values']);
    }

    /*
     * Policies
     * */

    /**
     * @param Attribute $record
     *
     * @return bool
     */
    public static function canDelete(Model $record): bool
    {
        return $record->values_count === 0;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AttributeTranslationKey::class;
    }
}
