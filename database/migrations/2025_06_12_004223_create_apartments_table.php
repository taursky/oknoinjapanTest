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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->index();
            $table->string('owner_name')->index();
            $table->string('address')->index();
            $table->integer('bedrooms')->index();
            $table->integer('house_floors')->index();
            $table->integer('floor')->index();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('apartments_options', function (Blueprint $table) {
            $table->unsignedInteger('apartment_id');
            $table->unsignedInteger('options_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments_options');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('options');
        Schema::dropIfExists('apartments');
    }
};
