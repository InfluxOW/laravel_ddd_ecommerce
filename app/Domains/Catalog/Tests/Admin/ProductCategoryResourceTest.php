<?php

namespace App\Domains\Catalog\Tests\Admin;

use App\Application\Tests\Admin\AdminCrudTestCase;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\CreateProductCategory;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\EditProductCategory;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ListProductCategories;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource\Pages\ViewProductCategory;
use App\Domains\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListProductCategories::class;
    protected ?string $createRecord = CreateProductCategory::class;
    protected ?string $viewRecord = ViewProductCategory::class;
    protected ?string $editRecord = EditProductCategory::class;

    protected array $seeders = [
        ProductCategorySeeder::class,
        ProductAttributeSeeder::class,
        ProductSeeder::class,
        ProductAttributeValueSeeder::class,
        ProductPriceSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return ProductCategory::query()->first();
    }
}
