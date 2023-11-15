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
            $table->string('name');
            $table->integer('client_id');
            $table->string('tramo_id');
            $table->integer('screen_id');
            $table->time('time');
            $table->date('date');
            $table->integer('duration');
            $table->text('files_name');
            $table->string('path');
            $table->tinyInteger('type')->default(0);
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
