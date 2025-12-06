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
        Schema::table('ihm_m_clusters', function (Blueprint $table) {
            $table->integer('total_employees')->default(0)->after('active_flag');
            $table->integer('total_securities')->default(0)->after('total_employees');
            $table->integer('total_residents')->default(0)->after('total_securities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_clusters', function (Blueprint $table) {
            $table->dropColumn(['total_employees', 'total_securities', 'total_residents']);
        });
    }
};
