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
        Schema::table('ihm_m_cluster_d_residents', function (Blueprint $table) {
            $table->string('house_block', 15)->nullable()->after('ipl_price');
            $table->string('house_number', 15)->nullable()->after('house_block');
            $table->integer('rw')->nullable()->after('house_status');
            $table->integer('rt')->nullable()->after('rw');
            $table->text('address')->nullable()->after('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_cluster_d_residents', function (Blueprint $table) {
            //
        });
    }
};
