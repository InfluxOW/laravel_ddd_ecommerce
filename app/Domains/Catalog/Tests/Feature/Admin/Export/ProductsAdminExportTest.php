<?php

namespace App\Domains\Catalog\Tests\Feature\Admin\Export;

use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Domains\Admin\Tests\AdminExportTest;
use App\Domains\Catalog\Admin\Resources\ProductResource\Pages\ListProducts;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;

final class ProductsAdminExportTest extends AdminExportTest
{
    protected string $listRecords = ListProducts::class;

    protected static array $seeders = [
        ProductCategorySeeder::class,
        ProductSeeder::class,
        ProductPriceSeeder::class,
        AttributeSeeder::class,
        ProductAttributeValueSeeder::class,
    ];
}
