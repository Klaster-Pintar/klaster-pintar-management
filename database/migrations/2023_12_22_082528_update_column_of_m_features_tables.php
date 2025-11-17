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
        Schema::table('ihm_m_features', function (Blueprint $table) {
            $table->bigInteger('category_id')->nullable()->after('description');
            $table->string('model', 100)->nullable()->after('logo_img_url');
            $table->string('method', 100)->nullable()->after('model');
            $table->double('price', 22,4)->default(0)->after('model');
            $table->double('discount', 5,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ihm_m_features', function (Blueprint $table) {
            //
        });
    }
};
