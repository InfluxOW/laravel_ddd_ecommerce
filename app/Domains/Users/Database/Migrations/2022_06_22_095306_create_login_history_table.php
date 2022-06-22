<?php

use App\Domains\Users\Models\User;
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
        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->string('device', 30)->nullable();
            $table->string('platform', 30)->nullable();
            $table->string('platform_version', 30)->nullable();
            $table->string('browser', 30)->nullable();
            $table->string('browser_version', 30)->nullable();
            $table->string('ip', 39)->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->string('region_code', 5)->nullable();
            $table->string('region_name', 30)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name', 30)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('zip', 20)->nullable();
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
        Schema::dropIfExists('login_history');
    }
};
