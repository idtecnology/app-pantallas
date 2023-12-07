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
        Schema::create('screens', function (Blueprint $table) {
            $table->id('_id');
            $table->string('nombre');
            $table->string('direccion');
            $table->text('imagen');
            $table->time('hora_encendido');
            $table->time('horario_apagado');
            $table->text('url_google_maps');
            $table->string('proximo_horario_disponible');
            $table->string('ultimo_dia_compra');
            $table->string('aspect_ratio');
            $table->json('dimension_px');
            $table->json('dimension_mts_marco');
            $table->json('dimension_mts_pantalla');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
