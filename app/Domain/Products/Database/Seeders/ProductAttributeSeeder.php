<?php

namespace App\Domain\Products\Database\Seeders;

use App\Domain\Products\Enums\ProductAttributeValuesType;
use App\Domain\Products\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ProductAttributeValuesType::cases() as $type) {
            ProductAttribute::factory()->count(3)->create(['values_type' => $type]);
        }
    }
}
