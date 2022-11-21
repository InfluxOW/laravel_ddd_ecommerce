<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Components\Attributable\Database\Seeders\AttributeValueSeeder;
use App\Domains\Catalog\Models\Product;
use App\Infrastructure\Abstracts\Database\Seeder;

final class ProductAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(AttributeValueSeeder::class, false, ['attributableModels' => [Product::class]]);
    }
}
