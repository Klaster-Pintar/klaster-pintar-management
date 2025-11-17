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
        Schema::create('ihm_t_assignment_house_sit_s', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_t_assignment_house_sit_id');
            $table->string('report_title');
            $table->text('report_detail');
            $table->string('picture1_url')->nullable();
            $table->string('picture2_url')->nullable();
            $table->string('picture3_url')->nullable();
            $table->string('picture4_url')->nullable();
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
        Schema::dropIfExists('ihm_t_assignment_house_sit_s');
    }
};
