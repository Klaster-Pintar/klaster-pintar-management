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
        Schema::create(getTablePrefix().'t_backlog_d_stories_s_acs_d_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger(getTablePrefix().'t_backlog_d_stories_s_acs_id');
            $table->boolean('checked')->default(false);
            $table->smallInteger('order')->default(0);
            $table->string('value', 500);
            $table->boolean('fixed')->default(false);
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
        Schema::dropIfExists(getTablePrefix().'t_backlog_d_stories_s_acs_d_values');
    }
};
