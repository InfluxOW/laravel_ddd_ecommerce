<?php

use App\Domains\Cart\Models\Cart;
use App\Domains\Catalog\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Cart::class, 'cart_id')->constrained('carts');
            $table->unsignedSmallInteger('quantity');
            $table->unsignedInteger('price_item');
            $table->unsignedInteger('price_item_discounted');
            $table->unsignedInteger('price_total');
            $table->unsignedInteger('price_total_discounted');

            $table->foreignIdFor(Product::class, 'product_id')->constrained('products');
            $table->string('product_title');
            $table->string('product_description', 3000);

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
        Schema::dropIfExists('cart_items');
    }
}
