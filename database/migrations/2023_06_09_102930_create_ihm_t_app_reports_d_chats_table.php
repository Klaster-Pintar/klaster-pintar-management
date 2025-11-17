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
        Schema::create('ihm_t_app_reports_d_chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_t_app_reports_id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('staff_id')->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('ihm_t_app_reports_d_chats');
    }
};
