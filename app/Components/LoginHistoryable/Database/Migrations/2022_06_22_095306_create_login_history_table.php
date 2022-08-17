<?php

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
        Schema::enablePostgisIfNotExists();

        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->morphs('login_historyable');
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device', 30)->nullable();
            $table->string('platform', 30)->nullable();
            $table->string('platform_version', 30)->nullable();
            $table->string('browser', 30)->nullable();
            $table->string('browser_version', 30)->nullable();
            $table->string('region_code', 5)->nullable();
            $table->string('region_name', 50)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->point('location')->nullable();
            $table->string('zip', 20)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disablePostgisIfExists();

        Schema::dropIfExists('login_history');
    }
};
