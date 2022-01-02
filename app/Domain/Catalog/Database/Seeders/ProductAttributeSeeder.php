<?php

namespace App\Domain\Catalog\Database\Seeders;

use App\Domain\Catalog\Enums\ProductAttributeValuesType;
use App\Domain\Catalog\Models\ProductAttribute;
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
