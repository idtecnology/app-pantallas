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
        Schema::create('tramos', function (Blueprint $table) {
            $table->id('_id');
            $table->string('tramo_id')->nullable();
            $table->integer('screen_id');
            $table->date('fecha');
            $table->integer('duracion')->default(600);
            $table->time('tramos');
            $table->boolean('isActive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramos');
    }
};
