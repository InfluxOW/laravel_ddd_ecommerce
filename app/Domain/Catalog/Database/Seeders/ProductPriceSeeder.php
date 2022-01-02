<?php

namespace App\Domain\Catalog\Database\Seeders;

use App\Domain\Catalog\Models\Generic\ProductsSettings;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ProductsSettings $settings)
    {
        $settings->available_currencies = app()->runningUnitTests() ? ['USD', 'RUB'] : ['USD', 'RUB', 'EUR', 'GBP'];
        $settings->save();

        foreach (Product::query()->whereDoesntHave('prices')->get() as $product) {
            foreach ($settings->available_currencies as $currency) {
                ProductPrice::factory()->for($product, 'product')->create(['currency' => $currency]);
            }
        }
    }
}
