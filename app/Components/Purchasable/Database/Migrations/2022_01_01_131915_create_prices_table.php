<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->morphs('purchasable');
            $table->unsignedInteger('price');
            $table->unsignedInteger('price_discounted')->nullable();
            $table->string('currency', 3);
            $table->timestamps();

            $table->unique(['purchasable_type', 'purchasable_id', 'currency']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
};
