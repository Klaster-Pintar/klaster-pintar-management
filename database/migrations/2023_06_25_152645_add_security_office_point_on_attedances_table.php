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
        Schema::table('ihm_t_security_attendances', function (Blueprint $table) {
            $table->tinyInteger('radius_checkin')->default(5)->after('security_id');
            $table->point('office_checkin_point')->nullable()->after('pt_checkin');
            $table->double('distance_checkin', 24,2)->nullable()->after('checkin_point');

            $table->tinyInteger('radius_checkout')->default(5)->after('checkin_inside');
            $table->point('office_checkout_point')->nullable()->after('pt_checkout');
            $table->double('distance_checkout', 24,2)->nullable()->after('checkout_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_security_attendances', function (Blueprint $table) {
            $table->dropColumn('radius_checkin');
            $table->dropColumn('office_checkin_point');
            $table->dropColumn('distance_checkin');
            $table->dropColumn('radius_checkout');
            $table->dropColumn('office_checkout_point');
            $table->dropColumn('distance_checkout');
        });
    }
};
