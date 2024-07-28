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
        Schema::create('serwisowane_pojazdy', function (Blueprint $table) {
            $table->id('id_serwisu');
            $table->unsignedBigInteger('id_pracownika');
            $table->unsignedBigInteger('id_pojazdu');
            $table->string('opis_usterki');
            $table->date('data_poczatku_serwisu');
            $table->string('status_serwisu');
            $table->date('data_konca_serwisu')->nullable();
            $table->foreign('id_pracownika')->references('id_pracownika')->on('pracownicy');
            $table->foreign('id_pojazdu')->references('id_pojazdu')->on('pojazdy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serwisowane_pojazdy');
    }
};
