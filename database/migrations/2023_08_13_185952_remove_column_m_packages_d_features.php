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
        Schema::table('ihm_m_packages_d_features', function (Blueprint $table) {
            $table->dropColumn('total_admin_account');
            $table->dropColumn('total_security_account');
            $table->dropColumn('total_staff_account');
            $table->dropColumn('checkpoint_type');
            $table->dropColumn('total_patrol_template');
            $table->dropColumn('total_security_office');
            $table->string('value')->after('ihm_m_packages_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_packages_d_features', function (Blueprint $table) {
            //
        });
    }
};
