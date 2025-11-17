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
        Schema::create('ihm_m_cluster_d_salaries_s_tenures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_m_cluster_d_salaries_id');
            $table->integer('year');
            $table->double('salary', 22,4);
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
        Schema::dropIfExists('ihm_m_cluster_d_salaries_s_tenures');
    }
};
