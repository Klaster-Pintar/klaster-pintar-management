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
        Schema::table('ihm_t_subscriptions', function (Blueprint $table) {
            $table->double('admin_fee', 22,4)->default(0)->after('discount');
            $table->integer('unicode_payment')->after('admin_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_subscriptions', function (Blueprint $table) {
            $table->dropColumn('admin_fee');
            $table->dropColumn('unicode_payment');
        });
    }
};
