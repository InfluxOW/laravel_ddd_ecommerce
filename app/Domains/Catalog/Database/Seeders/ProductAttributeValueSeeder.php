<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductAttribute;
use App\Domains\Catalog\Models\ProductAttributeValue;
use App\Infrastructure\Abstracts\Seeder;
use Exception;

final class ProductAttributeValueSeeder extends Seeder
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
