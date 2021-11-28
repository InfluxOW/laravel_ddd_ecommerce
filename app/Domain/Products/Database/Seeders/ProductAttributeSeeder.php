<?php

namespace App\Domain\Products\Database\Seeders;

use App\Domain\Products\Enums\ProductAttributeType;
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
        foreach (ProductAttributeType::cases() as $type) {
            ProductAttribute::factory()->count(3)->create(['type' => $type]);
        }
    }
}
