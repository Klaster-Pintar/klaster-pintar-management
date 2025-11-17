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
            $table->string('invoice_code', 225)->change();
            $table->string('proof_payment_img_url', 300)->nullable()->after('paid_at');
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
