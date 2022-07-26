<?php

namespace App\Domains\Admin\Admin\Components\Cards;

use App\Domains\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;

final class TimestampsCard extends Card
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Placeholder::makeTranslated(AdminTimestampsCardTranslationKey::CREATED_AT)
                ->content(fn (?Model $record): string => isset($record->created_at) ? $record->created_at->diffForHumans() : '-'),
            Placeholder::makeTranslated(AdminTimestampsCardTranslationKey::UPDATED_AT)
                ->content(fn (?Model $record): string => isset($record->updated_at) ? $record->updated_at->diffForHumans() : '-'),
        ]);
    }
}
