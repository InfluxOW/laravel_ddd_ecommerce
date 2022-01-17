<?php

use App\Domains\Cart\Models\Cart;
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

            $table->foreignIdFor(Cart::class, 'cart_id')->constrained('carts')->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->unsignedInteger('price_item');
            $table->unsignedInteger('price_item_discounted');
            $table->unsignedInteger('price_total');
            $table->unsignedInteger('price_total_discounted');

            $table->morphs('purchasable');
            $table->text('purchasable_data');

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
