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
        Schema::create('pojazdy', function (Blueprint $table) {
            $table->id('id_pojazdu');
            $table->string('vin');
            $table->unsignedBigInteger('id_cechy_pojazdu');
            $table->integer('rok_produkcji');
            $table->integer('przebieg');
            $table->integer('pojemnosc_silnika');
            $table->integer('moc_silnika');
            $table->string('rodzaj_paliwa');
            $table->integer('liczba_drzwi');
            $table->integer('liczba_miejsc');
            $table->decimal('cena', 10, 2);
            $table->unsignedBigInteger('id_wlasciciela');
            $table->string('status_pojazdu');
            $table->foreign('id_cechy_pojazdu')->references('id_cechy_pojazdu')->on('cechy_pojazdu');
            $table->foreign('id_wlasciciela')->references('id_klienta')->on('klienci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pojazdy');
    }
};
