<?php

use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductCategory::class, 'category_id')->constrained('product_categories');
            $table->foreignIdFor(Product::class, 'product_id')->constrained('products');
            $table->unique(['category_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories_products');
    }
}
