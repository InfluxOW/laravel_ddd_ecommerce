<?php

namespace App\Domains\Catalog\Tests\Feature\Admin\Export;

use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Domains\Admin\Tests\Feature\Admin\ExportTest;
use App\Domains\Catalog\Admin\Resources\ProductResource\Pages\ListProducts;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;

final class ProductsExportTest extends ExportTest
{
    protected string $listRecords = ListProducts::class;

    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ProductPriceSeeder::class,
            AttributeSeeder::class,
            ProductAttributeValueSeeder::class,
        ]);
    }
}
