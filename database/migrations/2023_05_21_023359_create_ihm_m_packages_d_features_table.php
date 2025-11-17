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
        Schema::create('ihm_m_packages_d_features', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_m_packages_id');
            $table->tinyInteger('total_admin_account');
            $table->tinyInteger('total_security_account');
            $table->tinyInteger('total_staff_account');
            $table->string('checkpoint_type');
            $table->tinyInteger('total_patrol_template');
            $table->tinyInteger('total_security_office');
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
        Schema::dropIfExists('ihm_m_packages_d_features');
    }
};
