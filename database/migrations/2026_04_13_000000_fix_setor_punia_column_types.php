<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_setor_punia')) {
            return;
        }

        Schema::table('tb_setor_punia', function (Blueprint $table) {
            // Fix column types to match related table PKs (bigint unsigned)
            $table->unsignedBigInteger('id_keuangan')->nullable()->change();
            $table->unsignedBigInteger('id_data_banjar')->nullable()->change();
        });

        if (Schema::hasColumn('tb_setor_punia', 'id_data_banjar_tujuan')) {
            Schema::table('tb_setor_punia', function (Blueprint $table) {
                $table->unsignedBigInteger('id_data_banjar_tujuan')->nullable()->change();
            });
        }

        // Fix same issue in tb_saldo_kas
        if (Schema::hasTable('tb_saldo_kas') && Schema::hasColumn('tb_saldo_kas', 'id_data_banjar')) {
            Schema::table('tb_saldo_kas', function (Blueprint $table) {
                $table->unsignedBigInteger('id_data_banjar')->nullable()->change();
            });
        }

        // Fix same issue in tb_riwayat_bagi_hasil
        if (Schema::hasTable('tb_riwayat_bagi_hasil')) {
            Schema::table('tb_riwayat_bagi_hasil', function (Blueprint $table) {
                $table->unsignedBigInteger('id_pembayaran')->change();
                $table->unsignedBigInteger('id_data_banjar')->change();
            });
        }
    }

    public function down(): void
    {
        // Reverting to integer is risky if data exists, skip
    }
};
