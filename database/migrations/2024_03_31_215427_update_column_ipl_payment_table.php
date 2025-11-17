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
        Schema::table('ihm_t_ipl_payments', function (Blueprint $table) {
            $table->dropColumn('admin_fee');
            $table->double('app_fee', 22, 4)->nullable()->after('admin_fee');
            $table->double('service_fee', 22, 4)->nullable()->after('app_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_ipl_payments', function (Blueprint $table) {
            //
        });
    }
};
