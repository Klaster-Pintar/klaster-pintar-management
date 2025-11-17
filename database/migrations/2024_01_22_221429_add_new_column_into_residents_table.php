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
            $table->double('ipl_price', 22,4)->nullable()->after('resident_id');
            $table->string('house_status', 15)->nullable()->after('ipl_price');
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
