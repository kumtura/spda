<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_dana_punia', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 50)->nullable()->after('status_pembayaran')->comment('xendit, duitku, transfer_manual');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'bukti_transfer')) {
                $table->string('bukti_transfer', 255)->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'status_verifikasi')) {
                $table->string('status_verifikasi', 20)->default('pending')->after('bukti_transfer')->comment('pending, approved, rejected');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            if (Schema::hasColumn('tb_dana_punia', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }
            if (Schema::hasColumn('tb_dana_punia', 'bukti_transfer')) {
                $table->dropColumn('bukti_transfer');
            }
            if (Schema::hasColumn('tb_dana_punia', 'status_verifikasi')) {
                $table->dropColumn('status_verifikasi');
            }
            if (Schema::hasColumn('tb_dana_punia', 'catatan_verifikasi')) {
                $table->dropColumn('catatan_verifikasi');
            }
        });
    }
};
