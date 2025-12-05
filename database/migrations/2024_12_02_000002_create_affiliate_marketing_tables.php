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
        // Marketing Master Data
        Schema::create('ihm_m_marketings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Nama marketing');
            $table->string('phone', 20)->comment('No Telp');
            $table->string('cluster_affiliate_name', 100)->nullable()->comment('Nama cluster affiliate');
            $table->string('referral_code', 20)->unique()->comment('Kode referral unik');
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('id_card_number', 50)->nullable()->comment('No KTP');
            $table->date('join_date')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');

            // Audit fields
            $table->boolean('active_flag')->default(true);
            $table->foreignId('created_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');
            $table->foreignId('updated_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');
            $table->foreignId('deleted_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index('referral_code');
            $table->index('status');
        });

        // Marketing - Cluster Mapping (berhasil join)
        Schema::create('ihm_t_marketing_clusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('ihm_m_marketings')->onDelete('cascade');
            $table->foreignId('cluster_id')->constrained('ihm_m_clusters')->onDelete('cascade');
            $table->date('join_date')->comment('Tanggal cluster berhasil join via marketing');
            $table->decimal('commission_percentage', 5, 2)->default(0)->comment('Commission % untuk cluster ini');
            $table->decimal('commission_amount', 15, 2)->default(0)->comment('Total komisi yang didapat');
            $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['marketing_id', 'cluster_id']);
            $table->index('marketing_id');
            $table->index('cluster_id');
        });

        // Commission Settings (per marketing atau global)
        Schema::create('ihm_m_commission_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->nullable()->constrained('ihm_m_marketings')->onDelete('cascade')->comment('Null = global setting');
            $table->foreignId('cluster_id')->nullable()->constrained('ihm_m_clusters')->onDelete('cascade')->comment('Null = for all clusters');
            $table->decimal('commission_percentage', 5, 2)->default(0)->comment('Commission percentage');
            $table->decimal('fixed_amount', 15, 2)->default(0)->comment('Fixed commission amount');
            $table->enum('commission_type', ['Percentage', 'Fixed', 'Both'])->default('Percentage');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();

            $table->foreignId('created_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');
            $table->foreignId('updated_id')->nullable()->constrained('ihm_m_users')->onDelete('set null');

            $table->timestamps();

            $table->index('marketing_id');
            $table->index('cluster_id');
            $table->index('is_active');
        });

        // Marketing Revenue Tracking
        Schema::create('ihm_t_marketing_revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('ihm_m_marketings')->onDelete('cascade');
            $table->foreignId('cluster_id')->nullable()->constrained('ihm_m_clusters')->onDelete('set null');
            $table->date('revenue_date');
            $table->decimal('revenue_amount', 15, 2)->default(0)->comment('Revenue dari cluster');
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0)->comment('Komisi yang didapat');
            $table->enum('payment_status', ['Pending', 'Paid', 'Cancelled'])->default('Pending');
            $table->date('payment_date')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('marketing_id');
            $table->index('cluster_id');
            $table->index('revenue_date');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ihm_t_marketing_revenues');
        Schema::dropIfExists('ihm_m_commission_settings');
        Schema::dropIfExists('ihm_t_marketing_clusters');
        Schema::dropIfExists('ihm_m_marketings');
    }
};
