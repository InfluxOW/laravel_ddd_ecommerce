<?php

use App\Domains\Catalog\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $getPropertyName = fn (string $property): string => sprintf('%s.%s', DomainServiceProvider::NAMESPACE->value, $property);

        $this->migrator->add($getPropertyName('default_currency'), 'USD');
        $this->migrator->add($getPropertyName('available_currencies'), ['USD']);
    }
};
