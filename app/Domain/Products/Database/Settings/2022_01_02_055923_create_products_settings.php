<?php

use App\Domain\Products\Providers\DomainServiceProvider;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateProductsSettings extends SettingsMigration
{
    public function up(): void
    {
        $getPropertyName = fn (string $property): string => sprintf('%s.%s', DomainServiceProvider::TRANSLATION_NAMESPACE->value, $property);

        $this->migrator->add($getPropertyName('default_currency'), 'USD');
        $this->migrator->add($getPropertyName('available_currencies'), ['USD']);
    }
}
