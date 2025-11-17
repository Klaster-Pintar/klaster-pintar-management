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
        Schema::table(getTablePrefix().'e_approval', function (Blueprint $table) {
            $table->bigInteger(getTablePrefix().'m_approval_id')->after('id');
        });

        Schema::table(getTablePrefix().'e_approval_d', function (Blueprint $table) {
            $table->bigInteger(getTablePrefix().'m_approval_d_rules_id')->after(getTablePrefix().'e_approval_id');
            $table->bigInteger(getTablePrefix().'m_approval_d_configs_id')->after(getTablePrefix().'m_approval_d_rules_id');
            $table->bigInteger(getTablePrefix().'m_approval_d_excludes_id')->nullable()->after(getTablePrefix().'m_approval_d_configs_id');
            $table->boolean('is_full_approve')->default(false)->after('action_note');
            $table->boolean('is_skippable')->default(false)->after('is_full_approve');
            $table->boolean('is_able_to_skip')->default(false)->after('is_skippable');
            $table->boolean('send_wa_notif')->default(false)->after('is_able_to_skip');
            $table->boolean('send_email_notif')->default(false)->after('send_wa_notif');
            $table->integer('min_value')->nullable()->after('send_email_notif');
            $table->integer('max_value')->nullable()->after('min_value');
            $table->boolean('is_completed')->default(false)->after('max_value');
            $table->boolean('is_assigned')->default(false)->after('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(getTablePrefix().'e_approval', function (Blueprint $table) {
            $table->dropColumn(getTablePrefix().'m_approval_id');
        });

        Schema::table(getTablePrefix().'e_approval_d', function (Blueprint $table) {
            $table->dropColumn(getTablePrefix().'m_approval_d_rules_id');
            $table->dropColumn(getTablePrefix().'m_approval_d_configs_id');
            $table->dropColumn(getTablePrefix().'m_approval_d_excludes_id');
            $table->dropColumn('is_full_approve');
            $table->dropColumn('is_skippable');
            $table->dropColumn('is_able_to_skip');
            $table->dropColumn('send_wa_notif');
            $table->dropColumn('send_email_notif');
            $table->dropColumn('min_value');
            $table->dropColumn('max_value');
            $table->dropColumn('is_completed');
            $table->dropColumn('is_assigned');
        });
    }
};
