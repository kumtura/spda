<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tb_riwayat_bagi_hasil')) {
            Schema::table('tb_riwayat_bagi_hasil', function (Blueprint $table) {
                if (!Schema::hasColumn('tb_riwayat_bagi_hasil', 'override_status_setor_desa')) {
                    $table->boolean('override_status_setor_desa')->default(false)->after('status_setor_desa');
                }

                if (!Schema::hasColumn('tb_riwayat_bagi_hasil', 'override_status_setor_banjar')) {
                    $table->boolean('override_status_setor_banjar')->default(false)->after('status_setor_banjar');
                }
            });
        }

        if (Schema::hasTable('tb_dana_punia')) {
            Schema::table('tb_dana_punia', function (Blueprint $table) {
                if (!Schema::hasColumn('tb_dana_punia', 'catatan_hapus')) {
                    $table->text('catatan_hapus')->nullable()->after('catatan_verifikasi');
                }

                if (!Schema::hasColumn('tb_dana_punia', 'dihapus_oleh')) {
                    $table->unsignedInteger('dihapus_oleh')->nullable()->after('catatan_hapus');
                }

                if (!Schema::hasColumn('tb_dana_punia', 'tanggal_hapus')) {
                    $table->dateTime('tanggal_hapus')->nullable()->after('dihapus_oleh');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tb_riwayat_bagi_hasil')) {
            Schema::table('tb_riwayat_bagi_hasil', function (Blueprint $table) {
                $dropColumns = [];

                if (Schema::hasColumn('tb_riwayat_bagi_hasil', 'override_status_setor_desa')) {
                    $dropColumns[] = 'override_status_setor_desa';
                }

                if (Schema::hasColumn('tb_riwayat_bagi_hasil', 'override_status_setor_banjar')) {
                    $dropColumns[] = 'override_status_setor_banjar';
                }

                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('tb_dana_punia')) {
            Schema::table('tb_dana_punia', function (Blueprint $table) {
                $dropColumns = [];

                if (Schema::hasColumn('tb_dana_punia', 'catatan_hapus')) {
                    $dropColumns[] = 'catatan_hapus';
                }

                if (Schema::hasColumn('tb_dana_punia', 'dihapus_oleh')) {
                    $dropColumns[] = 'dihapus_oleh';
                }

                if (Schema::hasColumn('tb_dana_punia', 'tanggal_hapus')) {
                    $dropColumns[] = 'tanggal_hapus';
                }

                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }
    }
};