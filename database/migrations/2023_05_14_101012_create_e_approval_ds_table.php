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
        Schema::create(env("TABLE_PREFIX").'e_approval_d', function (Blueprint $table) {
            $table->id();
            $table->bigInteger(env("TABLE_PREFIX").'e_approval_id');
            $table->integer('level');
            $table->integer('level_order');
            $table->string('type');
            $table->bigInteger('action_role_id');
            $table->bigInteger('action_user_id')->nullable();
            $table->string('action')->nullable();
            $table->datetime('action_at')->nullable();
            $table->text('action_note')->nullable();
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
        Schema::dropIfExists(env("TABLE_PREFIX").'e_approval_d');
    }
};
