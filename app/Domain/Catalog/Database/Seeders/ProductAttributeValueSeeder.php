<?php

namespace App\Domain\Catalog\Database\Seeders;

use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductAttribute;
use App\Domain\Catalog\Models\ProductAttributeValue;
use Exception;
use Illuminate\Database\Seeder;

class ProductAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $attributes = ProductAttribute::query()->inRandomOrder()->get();

        foreach (Product::query()->whereDoesntHave('attributeValues')->get() as $product) {
            foreach ($attributes->take(app()->runningUnitTests() ? 3 : random_int(3, 8)) as $attribute) {
                ProductAttributeValue::factory()->for($product, 'product')->for($attribute, 'attribute')->create();
            }
        }
    }
}
