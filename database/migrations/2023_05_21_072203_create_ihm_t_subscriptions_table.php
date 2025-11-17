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
        Schema::create('ihm_t_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cluster_id');
            $table->bigInteger('package_id');
            $table->decimal('price', 22,4);
            $table->tinyInteger('months');
            $table->date('expired_at');
            $table->boolean('active')->default(true);
            $table->string('code', 10)->unique();
            $table->string('status', 20)->default('PENDING');
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
        Schema::dropIfExists('ihm_t_subscriptions');
    }
};
