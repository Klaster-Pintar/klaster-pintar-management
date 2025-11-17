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
        Schema::table('ihm_m_users', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->integer('rt')->nullable()->after('regency_id');
            $table->integer('rw')->nullable()->after('rt');
            $table->string('r_code')->nullable()->after('rw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_users', function (Blueprint $table) {
            //
        });
    }
};
