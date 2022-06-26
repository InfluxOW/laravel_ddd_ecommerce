<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductPrice;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Infrastructure\Abstracts\Database\Seeder;
use Illuminate\Support\LazyCollection;

final class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CatalogSettings $settings)
    {
        $settings->available_currencies = app()->runningUnitTests() ? ['USD', 'RUB'] : ['USD', 'RUB', 'EUR', 'GBP'];
        $settings->save();

        $productPricesRows = [];
        foreach (Product::query()->whereDoesntHave('prices')->get(['id']) as $product) {
            foreach ($settings->available_currencies as $currency) {
                $productPricesRows[] = ProductPrice::factory()->for($product, 'product')->make(['currency' => $currency])->getRawAttributes(['id']);
            }
        }

        $this->insertByChunks((new ProductPrice())->getTable(), LazyCollection::make($productPricesRows), 50, 10);
    }
}
