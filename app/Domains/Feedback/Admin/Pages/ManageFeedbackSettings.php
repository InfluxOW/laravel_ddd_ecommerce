<?php

namespace App\Domains\Feedback\Admin\Pages;

use App\Domains\Admin\Admin\Abstracts\SettingsPage;
use App\Domains\Admin\Traits\HasNavigationSort;
use App\Domains\Admin\Traits\Translation\HasTranslatableAdminLabels;
use App\Domains\Admin\Traits\Translation\TranslatableAdminPage;
use App\Domains\Feedback\Enums\Translation\FeedbackSettingsTranslationKey;
use App\Domains\Feedback\Models\Settings\FeedbackSettings;
use Filament\Forms\Components\TextInput;

class ManageFeedbackSettings extends SettingsPage
{
    use TranslatableAdminPage;
    use HasTranslatableAdminLabels;
    use HasNavigationSort;

    protected static string $settings = FeedbackSettings::class;

    protected static ?string $slug = 'settings/feedback';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected function getFormSchema(): array
    {
        return self::setTranslatableLabels([
            TextInput::make(FeedbackSettingsTranslationKey::FEEDBACK_LIMIT_PER_HOUR->value)
                ->nullable()
                ->integer(),
        ]);
    }

    /*
     * Translation
     * */

    protected static function getTranslationKeyClass(): string
    {
        return FeedbackSettingsTranslationKey::class;
    }
}
