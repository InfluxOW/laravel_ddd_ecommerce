<?php

namespace App\Domain\Admin\Panel\Components\Cards;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;

class TimestampsCard extends Card
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Placeholder::make('created_at')
                ->label('Created at')
                ->content(fn (?Model $record): string => ($record === null) ? '-' : $record->created_at->diffForHumans()),
            Placeholder::make('updated_at')
                ->label('Last Modified At')
                ->content(fn (?Model $record): string => ($record === null) ? '-' : $record->updated_at->diffForHumans()),
        ]);
    }
}
