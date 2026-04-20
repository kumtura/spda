<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_punia_pendatang')) {
            return;
        }

        DB::statement("ALTER TABLE tb_punia_pendatang MODIFY status_pembayaran VARCHAR(50) NOT NULL DEFAULT 'belum_bayar'");
        DB::statement("ALTER TABLE tb_punia_pendatang MODIFY metode_pembayaran VARCHAR(50) NULL");

        Schema::table('tb_punia_pendatang', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_punia_pendatang', 'xendit_id')) {
                $table->string('xendit_id')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('tb_punia_pendatang', 'payment_data')) {
                $table->longText('payment_data')->nullable()->after('xendit_id');
            }
            if (!Schema::hasColumn('tb_punia_pendatang', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('tb_punia_pendatang', 'status_verifikasi')) {
                $table->string('status_verifikasi', 30)->nullable()->after('bukti_transfer');
            }
            if (!Schema::hasColumn('tb_punia_pendatang', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('tb_punia_pendatang')) {
            return;
        }

        Schema::table('tb_punia_pendatang', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['xendit_id', 'payment_data', 'bukti_transfer', 'status_verifikasi', 'catatan_verifikasi'] as $column) {
                if (Schema::hasColumn('tb_punia_pendatang', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });

        DB::statement("ALTER TABLE tb_punia_pendatang MODIFY status_pembayaran ENUM('belum_bayar', 'lunas') NOT NULL DEFAULT 'belum_bayar'");
        DB::statement("ALTER TABLE tb_punia_pendatang MODIFY metode_pembayaran ENUM('cash', 'qris') NULL");
    }
};