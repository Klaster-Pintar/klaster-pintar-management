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
        Schema::create('ihm_t_absences', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->bigInteger('cluster_id')->nullable();
            $table->bigInteger('role_id')->nullable();
            $table->date('date');
            $table->bigInteger('type_id');
            $table->text('detail');
            $table->string('contact_number')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('ihm_t_absences');
    }
};
