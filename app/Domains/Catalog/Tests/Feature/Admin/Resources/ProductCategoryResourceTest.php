<?php

namespace App\Domains\Catalog\Tests\Feature\Admin\Resources;

use App\Application\Tests\Feature\Admin\AdminCrudTestCase;
use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;

final class ProductCategoryResourceTest extends AdminCrudTestCase
{
    protected static string $resource = ProductCategoryResource::class;

    protected array $seeders = [
        ProductCategorySeeder::class,
        ProductSeeder::class,
        ProductPriceSeeder::class,
        AttributeSeeder::class,
        ProductAttributeValueSeeder::class,
    ];
}
