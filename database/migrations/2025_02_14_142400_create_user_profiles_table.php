<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};


class CreateUserProfilesTable extends Migration
{
    public function up()
{
    Schema::create('user_profiles', function (Blueprint $table) {
        $table->id();
        $table->string('user_name');
        $table->string('email')->unique();
        $table->string('home_page')->nullable();
        $table->string('profile_image')->nullable();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}