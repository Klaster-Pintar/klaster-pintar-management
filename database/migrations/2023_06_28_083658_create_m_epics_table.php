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
        Schema::create(getTablePrefix().'m_epic', function (Blueprint $table) {
            $table->id();
            $table->string('feature')->nullable();
            $table->bigInteger('stack_category_id')->nullable();
            $table->string('key')->nullable();
            $table->string('priority')->nullable();
            $table->tinyInteger('sprint')->nullable();
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
        Schema::dropIfExists(getTablePrefix().'m_epic');
    }
};
