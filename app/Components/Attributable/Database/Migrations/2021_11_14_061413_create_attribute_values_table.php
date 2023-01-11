<?php

namespace App\Components\Attributable;

use App\Components\Attributable\Models\Attribute;
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
        Schema::create('attribute_values', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};
