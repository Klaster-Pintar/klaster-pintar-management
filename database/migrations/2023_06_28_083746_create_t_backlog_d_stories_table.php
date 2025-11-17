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
        Schema::create(getTablePrefix().'t_backlog_d_stories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger(env('TABLE_PREFIX').'t_backlog_id');
            $table->string('code', 10);
            $table->string('user_story_title', 100);
            $table->text('story');
            $table->datetime('due_date')->nullable();
            $table->bigInteger('status_id')->nullable();
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
        Schema::dropIfExists(getTablePrefix().'t_backlog_d_stories');
    }
};
