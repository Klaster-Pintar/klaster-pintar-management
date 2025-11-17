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
            $table->double('discount', 5,2)->nullable()->after('months');
            $table->double('total', 22,4)->after('discount');
            $table->dropColumn('expired_at');
            $table->dropColumn('active');
            $table->dropColumn('code');
            $table->string('invoice_code', 10)->unique()->after('total');
            $table->json('payment_method')->after('invoice_code');
            $table->json('package')->after('payment_method');
            $table->boolean('paid_off')->default(false)->after('package');
            $table->datetime('paid_at')->nullable()->after('paid_off');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_subscriptions', function (Blueprint $table) {
            //
        });
    }
};
