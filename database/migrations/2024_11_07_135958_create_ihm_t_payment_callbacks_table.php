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
        Schema::create('ihm_t_payment_callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->nullable();
            $table->unsignedBigInteger('total');
            $table->json('callback_notification');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ihm_t_payment_callbacks');
    }
};
