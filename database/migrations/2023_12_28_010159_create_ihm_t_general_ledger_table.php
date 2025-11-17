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
        Schema::create('ihm_t_general_ledger', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('relation_table')->nullable();
            $table->bigInteger('relation_id')->nullable();
            $table->text('description')->nullable();
            $table->double('debit', 22,4)->nullable();
            $table->double('credit', 22,4)->nullable();
            $table->double('saldo', 22,4)->nullable();
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
        Schema::dropIfExists('ihm_t_general_ledger');
    }
};
