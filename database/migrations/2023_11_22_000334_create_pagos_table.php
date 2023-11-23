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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('_id');
            $table->string('client_id');
            $table->string('media_id')->nullable();
            $table->string('collection_id');
            $table->string('collection_status');
            $table->string('payment_id');
            $table->string('status');
            $table->string('payment_type');
            $table->string('preference_id');
            $table->string('merchant_order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
