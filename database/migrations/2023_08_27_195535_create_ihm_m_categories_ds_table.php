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
        Schema::create('ihm_m_categories_d', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_m_categories_id');
            $table->string('title');
            $table->string('subtitle', 400)->nullable();
            $table->string('screenshoot_app_img_url');
            $table->boolean('is_new')->default(false);
            $table->bigInteger('created_id');
            $table->bigInteger('updated_id')->nullable();
            $table->bigInteger('deleted_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ihm_m_categories_ds');
    }
};
