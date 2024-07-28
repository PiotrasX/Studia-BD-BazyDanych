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
        Schema::create('wystawione_pojazdy_sprzedaz', function (Blueprint $table) {
            $table->id('id_ogloszenia');
            $table->unsignedBigInteger('id_pojazdu');
            $table->date('data_wystawienia');
            $table->string('status_ogloszenia');
            $table->date('data_zakonczenia')->nullable();
            $table->foreign('id_pojazdu')->references('id_pojazdu')->on('pojazdy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wystawione_pojazdy_sprzedaz');
    }
};
