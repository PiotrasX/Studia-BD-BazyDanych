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
        Schema::create('sprzedane_pojazdy', function (Blueprint $table) {
            $table->id('id_sprzedazy');
            $table->unsignedBigInteger('id_pojazdu');
            $table->unsignedBigInteger('id_kupujacego');
            $table->date('data_sprzedazy');
            $table->foreign('id_pojazdu')->references('id_pojazdu')->on('pojazdy');
            $table->foreign('id_kupujacego')->references('id_klienta')->on('klienci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprzedane_pojazdy');
    }
};
