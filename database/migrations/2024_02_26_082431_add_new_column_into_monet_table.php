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
        Schema::table('ihm_t_money_transfer', function (Blueprint $table) {
            $table->bigInteger('cluster_id')->after('id')->nullable();
            $table->bigInteger('pg_id')->after('cluster_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_money_transfer', function (Blueprint $table) {
            //
        });
    }
};
