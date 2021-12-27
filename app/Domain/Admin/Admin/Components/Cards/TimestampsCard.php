<?php

namespace App\Domain\Admin\Admin\Components\Cards;

use App\Domain\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;
use App\Domain\Admin\Providers\DomainServiceProvider;
use App\Domain\Admin\Traits\Translation\TranslatableAdmin;
use App\Domain\Generic\Lang\Enums\TranslationNamespace;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Model;

class TimestampsCard extends Card
{
    use TranslatableAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(static::setTranslatableLabels([
            Placeholder::make(AdminTimestampsCardTranslationKey::CREATED_AT->value)
                ->content(fn (?Model $record): string => isset($record->created_at) ? $record->created_at->diffForHumans() : '-'),
            Placeholder::make(AdminTimestampsCardTranslationKey::UPDATED_AT->value)
                ->content(fn (?Model $record): string => isset($record->updated_at) ? $record->updated_at->diffForHumans() : '-'),
        ]));
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
        return AdminTimestampsCardTranslationKey::class;
    }
}
