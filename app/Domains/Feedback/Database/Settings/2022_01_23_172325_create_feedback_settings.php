<?php

use App\Domains\Feedback\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $getPropertyName = fn (string $property): string => sprintf('%s.%s', DomainServiceProvider::NAMESPACE->value, $property);

        $this->migrator->add($getPropertyName('feedback_limit_per_hour'), 1);
    }
};
