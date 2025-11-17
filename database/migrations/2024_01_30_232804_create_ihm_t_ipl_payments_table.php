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
        Schema::create('ihm_t_ipl_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_m_clusters_id');
            $table->bigInteger('resident_id');
            $table->tinyInteger('month');
            $table->integer('year');
            $table->double('ipl_price', 22,4);
            $table->string('invoice_code', 15)->unique();
            $table->string('status')->default('PENDING');
            $table->bigInteger('created_id');
            $table->bigInteger('updated_id')->nullable();
            $table->bigInteger('deleted_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ihm_t_ipl_payments');
    }
};
