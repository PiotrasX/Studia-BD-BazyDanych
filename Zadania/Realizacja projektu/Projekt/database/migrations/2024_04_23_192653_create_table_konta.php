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
        Schema::create('konta', function (Blueprint $table) {
            $table->id('id_konta');
            $table->string('login')->unique();
            $table->string('haslo');
            $table->string('typ_konta');
            $table->unsignedBigInteger('id_danych');
            $table->foreign('id_danych')->references('id_danych')->on('dane');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konta');
    }
};
