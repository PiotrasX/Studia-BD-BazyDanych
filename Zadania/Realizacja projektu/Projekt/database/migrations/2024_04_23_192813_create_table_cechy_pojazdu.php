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
        Schema::create('cechy_pojazdu', function (Blueprint $table) {
            $table->id('id_cechy_pojazdu');
            $table->string('marka');
            $table->string('model');
            $table->string('nadwozie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cechy_pojazdu');
    }
};
