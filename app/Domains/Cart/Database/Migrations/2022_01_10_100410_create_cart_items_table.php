<?php

use App\Domains\Cart\Models\Cart;
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
        Schema::create('cart_items', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
