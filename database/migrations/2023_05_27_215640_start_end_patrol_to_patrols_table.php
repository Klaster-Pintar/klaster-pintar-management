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
        Schema::table('ihm_t_log_patrols', function (Blueprint $table) {
            $table->dateTime('start_patrol_at')->nullable()->after('finished');
            $table->dateTime('finish_patrol_at')->nullable()->after('start_patrol_at');
            $table->time('duration')->nullable()->after('finish_patrol_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_t_log_patrols', function (Blueprint $table) {
            //
        });
    }
};
