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
        Schema::create('ihm_t_money_transfer', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->bigInteger('user_id');
            $table->bigInteger('type_id');
            $table->bigInteger('bank_code_id');
            $table->string('account_number', 50);
            $table->string('account_holder');
            $table->string('bank_type', 20);
            $table->double('amount', 22,4)->nullable();
            $table->double('admin_fee', 22,4)->nullable();
            $table->string('status')->default('PENDING');
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
        Schema::dropIfExists('ihm_t_money_transfer');
    }
};
