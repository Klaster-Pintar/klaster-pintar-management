<?php

use App\Enums\InvoiceTypeCategory;
use App\Enums\InvoiceTypeRecursive;
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
        Schema::create('ihm_m_invoice_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('category', array_column(InvoiceTypeCategory::cases(), 'value'));
            $table->enum('recursive', array_column(InvoiceTypeRecursive::cases(), 'value'))->nullable();
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
        Schema::dropIfExists('ihm_m_invoice_types');
    }
};
