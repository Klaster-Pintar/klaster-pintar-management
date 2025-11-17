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
        Schema::create('ihm_m_cluster_d_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ihm_m_clusters_id');
            $table->string('account_number');
            $table->string('account_holder')->nullable();
            $table->string('bank_type', 15);
            $table->bigInteger('bank_code_id');
            $table->boolean('is_verified')->default(false);
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
        Schema::dropIfExists('ihm_m_cluster_d_bank_accounts');
    }
};
