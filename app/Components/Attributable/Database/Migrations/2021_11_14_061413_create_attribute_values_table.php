<?php

use App\Components\Attributable\Models\Attribute;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->morphs('attributable');
            $table->foreignIdFor(Attribute::class, 'attribute_id')->constrained('attributes')->cascadeOnDelete();
            $table->string('value_string')->nullable();
            $table->unsignedInteger('value_integer')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->double('value_float')->nullable();
            $table->timestamps();

            $table->unique(['attributable_type', 'attributable_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_values');
    }
};
