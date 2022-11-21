<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_categories_products', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(ProductCategory::class, 'category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->foreignIdFor(Product::class, 'product_id')->constrained('products')->cascadeOnDelete();
            $table->unique(['category_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_products');
    }
};
