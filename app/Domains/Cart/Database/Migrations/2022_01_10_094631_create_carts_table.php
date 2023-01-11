<?php

namespace App\Domains\Cart;

use App\Domains\Users\Models\User;
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
        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('key')->index()->nullable();
            $table->string('currency');
            $table->unsignedInteger('price_items');
            $table->unsignedInteger('price_items_discounted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
