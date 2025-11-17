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
        Schema::create('ihm_m_clusters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patrol_type_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('picture')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('radius_checkin')->default(5);
            $table->tinyInteger('radius_patrol')->default(5);
            $table->boolean('active_flag')->default(true);
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
        Schema::dropIfExists('ihm_m_clusters');
    }
};
