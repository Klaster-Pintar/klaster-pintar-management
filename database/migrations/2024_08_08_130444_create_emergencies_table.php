<?php

use App\Enums\PanicButtonCategory;
use App\Enums\PanicButtonStatus;
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
        Schema::create('ihm_t_emergencies', function (Blueprint $table) {
            $table->id();

            // emergency main broadcasting data
            $table->unsignedBigInteger('cluster_id');
            $table->foreign('cluster_id')->references('id')->on('ihm_m_clusters');
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('ihm_m_users');
            $table->enum('status', array_column(PanicButtonStatus::cases(), 'value'))->default(PanicButtonStatus::BROADCAST->value);
            $table->enum('category', array_column(PanicButtonCategory::cases(), 'value'));
            $table->point('location');
            $table->string('location_address')->default('');

            // security going to location related data
            $table->unsignedBigInteger('security_id')->nullable();
            $table->foreign('security_id')->references('id')->on('ihm_m_users');
            $table->timestamp('security_towards_location_at')->nullable();
            $table->point('security_start_location')->nullable();

            // canceled related status
            $table->unsignedBigInteger('canceled_user_id')->nullable();
            $table->foreign('canceled_user_id')->references('id')->on('ihm_m_users');
            $table->timestamp('canceled_at')->nullable();

            // finished related status
            $table->unsignedBigInteger('finished_user_id')->nullable();
            $table->foreign('finished_user_id')->references('id')->on('ihm_m_users');
            $table->timestamp('finished_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergencies');
    }
};
