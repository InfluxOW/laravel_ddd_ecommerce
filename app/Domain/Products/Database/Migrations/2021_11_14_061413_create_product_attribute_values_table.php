<?php

use App\Domain\Products\Models\Product;
use App\Domain\Products\Models\ProductAttribute;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class, 'product_id')->constrained('products');
            $table->foreignIdFor(ProductAttribute::class, 'attribute_id')->constrained('product_attributes');
            $table->string('value_string')->nullable();
            $table->unsignedInteger('value_integer')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->double('value_float')->nullable();
            $table->timestamps();

            $table->unique(['attribute_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
}
