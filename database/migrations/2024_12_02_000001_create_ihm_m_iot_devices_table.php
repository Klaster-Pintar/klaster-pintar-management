<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ihm_m_iot_devices', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode unik device');
            $table->string('name', 100)->comment('Nama device');
            $table->string('type', 50)->comment('Tipe device (sensor, camera, etc)');
            $table->text('description')->nullable()->comment('Deskripsi device');

            // Relationship to cluster
            $table->foreignId('cluster_id')->nullable()->constrained('ihm_m_clusters')->onDelete('set null');

            // Hardware Status: Active, Inactive, Rusak
            $table->enum('hardware_status', ['Active', 'Inactive', 'Rusak'])->default('Active');

            // Network Status: Connected, Not Connected
            $table->enum('network_status', ['Connected', 'Not Connected'])->default('Not Connected');

            // Connection tracking
            $table->timestamp('last_connected_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->integer('signal_strength')->nullable()->comment('Signal strength in percentage (0-100)');

            // Additional metadata
            $table->string('firmware_version', 20)->nullable();
            $table->string('location', 200)->nullable()->comment('Physical location of device');

            // Standard audit fields
            $table->boolean('active_flag')->default(true);
            $table->foreignId('created_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');
            $table->foreignId('updated_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');
            $table->foreignId('deleted_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('cluster_id');
            $table->index('hardware_status');
            $table->index('network_status');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ihm_m_iot_devices');
    }
};
