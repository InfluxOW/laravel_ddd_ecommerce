<?php

namespace App\Components\Attributable\Database\Seeders;

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\Attribute;
use App\Infrastructure\Abstracts\Database\Seeder;

final class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (AttributeValuesType::cases() as $type) {
            Attribute::factory()->count(3)->create(['values_type' => $type]);
        }
    }
}
