<?php

namespace App\Domains\Admin\Admin\Components\Cards;

use App\Domains\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;

final class TimestampsCard extends Card
{
    use HasTranslatableAdminLabels;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(self::setTranslatableLabels([
            Placeholder::make(AdminTimestampsCardTranslationKey::CREATED_AT->value)
                ->content(fn (?Model $record): string => isset($record->created_at) ? $record->created_at->diffForHumans() : '-'),
            Placeholder::make(AdminTimestampsCardTranslationKey::UPDATED_AT->value)
                ->content(fn (?Model $record): string => isset($record->updated_at) ? $record->updated_at->diffForHumans() : '-'),
        ]));
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return AdminTimestampsCardTranslationKey::class;
    }
}
