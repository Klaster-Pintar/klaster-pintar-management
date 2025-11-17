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
            $table->double('admin_fee', 22, 4)->nullable()->after('ipl_price');
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
