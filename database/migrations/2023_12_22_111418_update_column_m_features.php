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
        Schema::table('ihm_m_features', function (Blueprint $table) {
            $table->boolean('show_feature')->default(true)->after('method');
            $table->boolean('is_comming_soon')->default(false)->after('show_feature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_features', function (Blueprint $table) {
            //
        });
    }
};
