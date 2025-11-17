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
        Schema::table('ihm_t_log_patrols_d', function (Blueprint $table) {
            $table->point('user_check_point')->nullable()->after('check_point');
            $table->tinyInteger('radius_patrol')->default(5)->after('user_check_point');
            $table->double('distance', 5,2)->nullable()->after('radius_patrol');
            $table->boolean('in_radius')->nullable()->after('distance');
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
