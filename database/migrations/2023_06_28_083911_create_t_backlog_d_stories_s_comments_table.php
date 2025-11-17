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
        Schema::create(getTablePrefix().'t_backlog_d_stories_s_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger(getTablePrefix().'t_backlog_d_stories_id');
            $table->bigInteger('user_id');
            $table->text('message');
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
        Schema::dropIfExists(getTablePrefix().'t_backlog_d_stories_s_comments');
    }
};
