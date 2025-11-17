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
        Schema::table('ihm_m_cluster_d_administrator', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->string('name', 50)->nullable()->after('ihm_m_clusters_id');
            $table->string('phone')->nullable()->after('name');
            $table->string('email')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_cluster_d_administrator', function (Blueprint $table) {
            //
        });
    }
};
