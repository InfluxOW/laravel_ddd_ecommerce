<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductAttribute;
use App\Domains\Catalog\Models\ProductAttributeValue;
use App\Infrastructure\Abstracts\Database\Seeder;
use Exception;
use Illuminate\Support\LazyCollection;

final class ProductAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        $attributes = ProductAttribute::query()->inRandomOrder()->get(['id', 'values_type']);

        $attributeValuesRows = [];
        foreach (Product::query()->whereDoesntHave('attributeValues')->get(['id']) as $product) {
            foreach ($attributes->take(app()->runningUnitTests() ? 4 : random_int(3, 8)) as $attribute) {
                $attributeValuesRows[] = ProductAttributeValue::factory()->for($product, 'product')->for($attribute, 'attribute')->make()->getRawAttributes(['id']);
            }
        }

        $this->insertByChunks((new ProductAttributeValue())->getTable(), LazyCollection::make($attributeValuesRows));
    }
}
