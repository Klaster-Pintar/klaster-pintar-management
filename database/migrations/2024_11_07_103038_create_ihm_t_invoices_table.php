<?php

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
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
        Schema::create('ihm_t_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cluster_resident_id');
            $table->foreign('cluster_resident_id')->references('id')->on('ihm_m_cluster_d_residents');

            $table->unsignedBigInteger('cluster_id');
            $table->foreign('cluster_id')->references('id')->on('ihm_m_clusters');

            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('ihm_m_users');

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('ihm_m_invoice_types');

            $table->tinyInteger('month');
            $table->integer('year');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('app_fee')->default(0);
            $table->unsignedBigInteger('service_fee')->default(0);
            
            $table->string('invoice_number', 30)->unique();
            $table->enum('status', array_column(InvoiceStatus::cases(), 'value'))->default(InvoiceStatus::DRAFT->value);
            $table->enum('payment_method', array_column(PaymentMethod::cases(), 'value'))->nullable();
            
            $table->json('payload')->nullable();

            $table->timestamp('callback_notified_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();

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
        Schema::dropIfExists('ihm_t_invoices');
    }
};
