<?php

namespace App\Components\Attributable\Database\Seeders;

use App\Components\Attributable\Database\Factories\AttributeValueFactory;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\Attribute;
use App\Infrastructure\Abstracts\Database\Seeder;
use Faker\Generator;

final class AttributeSeeder extends Seeder
{
    private const RELEASE_YEAR = 'Release Year';

    private const COLOR = 'Color';

    private const MANUFACTURER = 'Manufacturer';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAttributes();
        $this->setCustomAttributeValueHandlers();
    }

    private function createAttributes(): void
    {
        Attribute::factory()->create(['values_type' => AttributeValuesType::INTEGER, 'title' => 'Height, Cm', 'slug' => 'height']);
        Attribute::factory()->create(['values_type' => AttributeValuesType::INTEGER, 'title' => 'Width, Cm', 'slug' => 'width']);
        Attribute::factory()->create(['values_type' => AttributeValuesType::INTEGER, 'title' => 'Depth, Cm', 'slug' => 'depth']);
        Attribute::factory()->create(['values_type' => AttributeValuesType::INTEGER, 'title' => self::RELEASE_YEAR]);

        Attribute::factory()->create(['values_type' => AttributeValuesType::FLOAT, 'title' => 'Weight, Kg', 'slug' => 'weight']);

        Attribute::factory()->create(['values_type' => AttributeValuesType::STRING, 'title' => self::COLOR]);
        Attribute::factory()->create(['values_type' => AttributeValuesType::STRING, 'title' => self::MANUFACTURER]);

        Attribute::factory()->create(['values_type' => AttributeValuesType::BOOLEAN, 'title' => 'Gaming']);
        Attribute::factory()->create(['values_type' => AttributeValuesType::BOOLEAN, 'title' => 'NFC']);
    }

    private function setCustomAttributeValueHandlers(): void
    {
        AttributeValueFactory::$customAttributeValueHandlers[self::RELEASE_YEAR] = static fn (Generator $faker): int => (int) $faker->year;

        AttributeValueFactory::$customAttributeValueHandlers[self::COLOR] = static fn (Generator $faker): string => $faker->colorName;
        AttributeValueFactory::$customAttributeValueHandlers[self::MANUFACTURER] = static fn (Generator $faker): string => $faker->company;
    }
}
