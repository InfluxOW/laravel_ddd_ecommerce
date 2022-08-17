<?php

use App\Domains\Feedback\Models\Settings\FeedbackSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $getPropertyName = static fn (string $property): string => sprintf('%s.%s', FeedbackSettings::group(), $property);

        $this->migrator->add($getPropertyName('feedback_limit_per_hour'), 1);
    }
};
