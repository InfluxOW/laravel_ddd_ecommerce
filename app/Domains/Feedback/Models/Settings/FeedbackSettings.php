<?php

namespace App\Domains\Feedback\Models\Settings;

use App\Domains\Feedback\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Settings;

class FeedbackSettings extends Settings
{
    public ?int $feedback_limit_per_hour;

    public static function group(): string
    {
        return DomainServiceProvider::TRANSLATION_NAMESPACE->value;
    }
}
