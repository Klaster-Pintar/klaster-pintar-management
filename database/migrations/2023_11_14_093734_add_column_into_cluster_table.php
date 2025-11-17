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
            $table->string('address')->nullable()->after('radius_patrol');
            $table->bigInteger('province_id')->nullable()->after('address');
            $table->bigInteger('regency_id')->nullable()->after('province_id');
            $table->bigInteger('district_id')->nullable()->after('regency_id');
            $table->bigInteger('village_id')->nullable()->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_clusters', function (Blueprint $table) {
            //
        });
    }
};
