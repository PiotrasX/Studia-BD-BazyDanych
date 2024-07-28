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
        Schema::create('dane', function (Blueprint $table) {
            $table->id('id_danych');
            $table->string('imie');
            $table->string('nazwisko');
            $table->string('numer_telefonu');
            $table->string('email')->unique();
            $table->string('ulica');
            $table->string('numer_domu');
            $table->string('kod_pocztowy');
            $table->string('miejscowosc');
            $table->string('kraj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dane');
    }
};
