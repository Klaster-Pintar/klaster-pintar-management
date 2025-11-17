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
            $table->boolean('checkin_inside')->nullable()->after('checkin_point');
            $table->boolean('checkout_inside')->nullable()->after('checkout_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_security_attendances', function (Blueprint $table) {
            $table->dropColumn('checkin_inside');
            $table->dropColumn('checkout_inside');
        });
    }
};
