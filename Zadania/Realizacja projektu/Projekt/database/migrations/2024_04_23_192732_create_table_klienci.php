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
        Schema::create('klienci', function (Blueprint $table) {
            $table->id('id_klienta');
            $table->unsignedBigInteger('id_konta');
            $table->decimal('stan_konta', 10, 2);
            $table->foreign('id_konta')->references('id_konta')->on('konta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klienci');
    }
};
