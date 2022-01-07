<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Models\ProductAttribute;
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
