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
        Schema::create('campanias', function (Blueprint $table) {
            $table->id('_id');
            $table->string('name');
            $table->integer('screen_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('cant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanias');
    }
};
