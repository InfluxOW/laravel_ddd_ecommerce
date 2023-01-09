<?php

namespace App\Components\Attributable\Admin\Resources;

use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\CreateAttribute;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\EditAttribute;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\ListAttributes;
use App\Components\Attributable\Admin\Resources\AttributeResource\Pages\ViewAttribute;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Enums\Translation\AttributeTranslationKey;
use App\Components\Attributable\Models\Attribute;
use App\Domains\Admin\Admin\Abstracts\Resource;
use App\Domains\Common\Utils\LangUtils;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
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
                        Grid::make()
                            ->schema([
                                TextInput::makeTranslated(AttributeTranslationKey::TITLE)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set, $state): mixed => $set(AttributeTranslationKey::SLUG->value, Str::slug($state)))
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('Width')
                                    ->columnSpan(5),
                                TextInput::makeTranslated(AttributeTranslationKey::SLUG)
                                    ->required()
                                    ->minValue(2)
                                    ->maxLength(255)
                                    ->placeholder('width')
                                    ->columnSpan(3),
                            ])
                            ->columns(8)
                            ->columnSpan(2),
                        Select::makeTranslated(AttributeTranslationKey::VALUES_TYPE)
                            ->required()
                            ->options(collect(AttributeValuesType::cases())->reduce(fn (Collection $acc, AttributeValuesType $valuesType): Collection => tap($acc, static fn () => $acc->offsetSet($valuesType->value, LangUtils::translateEnum($valuesType))), collect([])))
                            ->searchable()
                            ->columnSpan(2),
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
            'index' => ListAttributes::route('/'),
            'create' => CreateAttribute::route('/create'),
            'edit' => EditAttribute::route('/{record}/edit'),
            'view' => ViewAttribute::route('/{record}'),
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
     */
    public static function canDelete(Model $record): bool
    {
        return $record->values_count === 0;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
