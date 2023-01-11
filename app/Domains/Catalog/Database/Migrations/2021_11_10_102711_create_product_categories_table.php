<?php

namespace App\Domains\Catalog;

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
        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description', 3000)->nullable();
            $table->boolean('is_visible');
            $table->boolean('is_displayable');
            $table->foreignIdFor(ProductCategory::class, 'parent_id')->nullable()->constrained($table->getTable())->cascadeOnDelete();
            $table->unsignedInteger('left');
            $table->unsignedInteger('right');
            $table->unsignedSmallInteger('depth')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
