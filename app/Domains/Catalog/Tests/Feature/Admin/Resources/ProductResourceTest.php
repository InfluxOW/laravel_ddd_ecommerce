<?php

namespace App\Domains\Catalog\Tests\Feature\Admin\Resources;

use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Domains\Admin\Tests\AdminCrudTestCase;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;

final class ProductResourceTest extends AdminCrudTestCase
{
    protected static string $resource = ProductResource::class;

    protected static array $seeders = [
        ProductCategorySeeder::class,
        ProductSeeder::class,
        ProductPriceSeeder::class,
        AttributeSeeder::class,
        ProductAttributeValueSeeder::class,
    ];
}
