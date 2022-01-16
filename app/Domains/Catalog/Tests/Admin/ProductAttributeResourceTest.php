<?php

namespace App\Domains\Catalog\Tests\Admin;

use App\Application\Tests\Admin\AdminCrudTestCase;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\CreateProductAttribute;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\EditProductAttribute;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\ListProductAttributes;
use App\Domains\Catalog\Admin\Resources\ProductAttributeResource\Pages\ViewProductAttribute;
use App\Domains\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domains\Catalog\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeResourceTest extends AdminCrudTestCase
{
    protected ?string $listRecords = ListProductAttributes::class;
    protected ?string $createRecord = CreateProductAttribute::class;
    protected ?string $viewRecord = ViewProductAttribute::class;
    protected ?string $editRecord = EditProductAttribute::class;

    protected array $seeders = [
        ProductAttributeSeeder::class,
    ];

    protected function getRecord(): ?Model
    {
        return ProductAttribute::query()->first();
    }
}
