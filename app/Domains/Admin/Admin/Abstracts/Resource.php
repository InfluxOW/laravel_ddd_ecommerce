<?php

namespace App\Domains\Admin\Admin\Abstracts;

use App\Domains\Admin\Admin\Components\Cards\TimestampsCard;
use App\Domains\Generic\Traits\Models\Searchable;
use Filament\Forms\Components\Grid;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\Resources\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Resource extends SimpleResource
{
    public static function getGlobalSearchResults(string $searchQuery): Collection
    {
        /** @var Model $model */
        $model = static::getModel();
        $traits = class_uses($model);

        if (is_array($traits) && isset($traits[Searchable::class])) {
            /** @phpstan-ignore-next-line */
            return $model::search($searchQuery)
                ->take(50)
                ->get()
                ->map(function (Model $record): ?GlobalSearchResult {
                    $url = static::getGlobalSearchResultUrl($record);

                    if (blank($url)) {
                        return null;
                    }

                    /** @var string $url */
                    return new GlobalSearchResult(
                        title: static::getGlobalSearchResultTitle($record),
                        url: $url,
                        details: static::getGlobalSearchResultDetails($record),
                    );
                })
                ->filter();
        }

        return parent::getGlobalSearchResults($searchQuery);
    }

    public static function creationForm(Form $form): Form
    {
        return static::form($form);
    }

    public static function editingForm(Form $form): Form
    {
        return static::form($form);
    }

    public static function viewingForm(Form $form): Form
    {
        $form = static::form($form);

        return Form::make()
            ->schema([
                Grid::make()
                    ->schema($form->getSchema())
                    ->columnSpan(2),
                TimestampsCard::make()
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
