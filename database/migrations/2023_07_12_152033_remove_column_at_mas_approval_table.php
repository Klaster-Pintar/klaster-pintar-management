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
        Schema::table('ihm_m_approval', function (Blueprint $table) {
            $table->dropColumn('modul');
            $table->dropColumn('submodul');
        });

        Schema::table('ihm_e_approval', function (Blueprint $table) {
            $table->dropColumn('company_id')->nullable();
            $table->dropColumn('trx_path');
            $table->dropColumn('modul');
            $table->dropColumn('form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
