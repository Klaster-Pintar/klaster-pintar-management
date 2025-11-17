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
        Schema::create(env("TABLE_PREFIX").'e_approval', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('trx_id');
            $table->string('trx_name');
            $table->string('trx_no');
            $table->string('trx_table');
            $table->date('trx_date');
            $table->bigInteger('trx_creator_id');
            $table->text('trx_note')->nullable();
            $table->string('trx_path'); // <-- perlu dihapus
            $table->string('modul'); // <-- perlu dihapus
            $table->string('form'); // <-- perlu dihapus
            $table->string('status');
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
        Schema::dropIfExists(env("TABLE_PREFIX").'e_approval');
    }
};
