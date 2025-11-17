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
        Schema::create('ihm_m_cluster_d_schedulers_s', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_m_cluster_d_schedulers_id');
            $table->tinyInteger('minute')->default(-1);
            $table->tinyInteger('hour')->default(-1);
            $table->tinyInteger('mday')->default(-1);
            $table->tinyInteger('month')->default(-1);
            $table->tinyInteger('wday')->default(-1);
            $table->string('crontab_expression');
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
        Schema::dropIfExists('ihm_m_cluster_d_schedulers_s');
    }
};
