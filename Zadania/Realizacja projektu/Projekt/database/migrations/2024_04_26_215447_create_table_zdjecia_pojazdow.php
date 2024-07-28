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
        Schema::create('zdjecia_pojazdow', function (Blueprint $table) {
            $table->id('id_zdjecia');
            $table->unsignedBigInteger('id_pojazdu');
            $table->string('nazwa_zdjecia');
            $table->foreign('id_pojazdu')->references('id_pojazdu')->on('pojazdy')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zdjecia_pojazdow');
    }
};
