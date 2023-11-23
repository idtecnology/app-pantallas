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
        Schema::create('media', function (Blueprint $table) {
            $table->id('_id');
            $table->string('name')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('tramo_id')->nullable();
            $table->integer('screen_id')->nullable();
            $table->integer('pago_id')->nullable();
            $table->string('preference_id')->nullable();
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->integer('duration')->nullable();
            $table->text('files_name');
            $table->tinyInteger('approved')->default(0);
            $table->tinyInteger('isPaid')->default(0);
            $table->tinyInteger('downloaded')->default(0);
            $table->boolean('isActive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
