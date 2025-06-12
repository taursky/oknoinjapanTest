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
            $table->string('owner_name')->index(); // Имя владельца
            $table->string('city')->index(); // Город
            $table->string('address')->index(); // Адрес
            $table->integer('bedrooms')->index(); // Кол-во спален
            $table->integer('house_floors')->index(); // Кол-во этажей в доме
            $table->integer('floor')->index(); // Этаж
            $table->text('options')->nullable(); // Опции (будем хранить как JSON)
            $table->decimal('price', 10, 2); // Стоимость
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
