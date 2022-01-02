<?php

namespace App\Domain\Products\Database\Seeders;

use App\Domain\Products\Models\Generic\ProductsSettings;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Models\ProductPrice;
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
