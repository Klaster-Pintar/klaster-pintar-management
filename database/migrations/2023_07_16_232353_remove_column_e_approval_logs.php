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
        Schema::table(env("TABLE_PREFIX").'e_approval_logs', function (Blueprint $table) {
            $table->dropColumn('modul');
            $table->dropColumn('form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(env("TABLE_PREFIX").'e_approval_logs', function (Blueprint $table) {
            $table->string('modul');
            $table->string('form');
        });
    }
};
