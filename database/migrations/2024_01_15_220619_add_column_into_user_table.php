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
            $table->string('house_block')->nullable()->after('rw');
            $table->string('house_number')->nullable()->after('house_block');
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
