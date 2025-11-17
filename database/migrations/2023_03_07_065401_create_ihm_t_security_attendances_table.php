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
        Schema::create('ihm_t_security_attendances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cluster_id');
            $table->bigInteger('security_id');
            $table->string('pt_checkin')->nullable();
            $table->point('checkin_point')->nullable();
            $table->string('pt_checkout')->nullable();
            $table->point('checkout_point')->nullable();
            $table->boolean('check_out')->default(false);
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
        Schema::dropIfExists('ihm_t_security_attendances');
    }
};
