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
        Schema::create('pracownicy', function (Blueprint $table) {
            $table->id('id_pracownika');
            $table->unsignedBigInteger('id_konta');
            $table->string('stanowisko');
            $table->foreign('id_konta')->references('id_konta')->on('konta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pracownicy');
    }
};
