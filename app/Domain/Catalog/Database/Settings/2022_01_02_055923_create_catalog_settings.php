<?php

use App\Domain\Catalog\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateCatalogSettings extends SettingsMigration
{
    public function up(): void
    {
        $getPropertyName = fn (string $property): string => sprintf('%s.%s', DomainServiceProvider::TRANSLATION_NAMESPACE->value, $property);

        $this->migrator->add($getPropertyName('default_currency'), 'USD');
        $this->migrator->add($getPropertyName('available_currencies'), ['USD']);
    }
}
