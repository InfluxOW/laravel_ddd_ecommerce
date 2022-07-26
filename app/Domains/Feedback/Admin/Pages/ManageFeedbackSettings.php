<?php

namespace App\Domains\Feedback\Admin\Pages;

use App\Domains\Admin\Admin\Abstracts\SettingsPage;
use App\Domains\Feedback\Enums\Translation\FeedbackSettingsTranslationKey;
use App\Domains\Feedback\Models\Settings\FeedbackSettings;
use Filament\Forms\Components\TextInput;

final class ManageFeedbackSettings extends SettingsPage
{
    protected static string $settings = FeedbackSettings::class;

    protected static ?string $slug = 'settings/feedback';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected function getFormSchema(): array
    {
        return [
            TextInput::makeTranslated(FeedbackSettingsTranslationKey::FEEDBACK_LIMIT_PER_HOUR)
                ->nullable()
                ->integer(),
        ];
    }
}
