<?php

namespace App\Domains\Catalog\Tests\Admin;

use App\Application\Tests\Admin\AdminCrudTestCase;
use App\Domains\Catalog\Admin\Resources\ProductResource\Pages\CreateProduct;
use App\Domains\Catalog\Admin\Resources\ProductResource\Pages\EditProduct;
use App\Domains\Catalog\Admin\Resources\ProductResource\Pages\ListProducts;
use App\Domains\Catalog\Admin\Resources\ProductResource\Pages\ViewProduct;
use App\Domains\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListProducts::class;
    protected ?string $createRecord = CreateProduct::class;
    protected ?string $viewRecord = ViewProduct::class;
    protected ?string $editRecord = EditProduct::class;

    protected array $seeders = [
        ProductCategorySeeder::class,
        ProductAttributeSeeder::class,
        ProductSeeder::class,
        ProductAttributeValueSeeder::class,
        ProductPriceSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return Product::query()->first();
    }
}
