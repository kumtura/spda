<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tb_dana_punia')) {
            Schema::table('tb_dana_punia', function (Blueprint $table) {
                if (!Schema::hasColumn('tb_dana_punia', 'verified_by')) {
                    $table->unsignedBigInteger('verified_by')->nullable()->after('catatan_verifikasi');
                }
            });
        }

        if (Schema::hasTable('tb_punia_pendatang')) {
            Schema::table('tb_punia_pendatang', function (Blueprint $table) {
                if (!Schema::hasColumn('tb_punia_pendatang', 'verified_by')) {
                    $table->unsignedBigInteger('verified_by')->nullable()->after('catatan_verifikasi');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tb_dana_punia') && Schema::hasColumn('tb_dana_punia', 'verified_by')) {
            Schema::table('tb_dana_punia', function (Blueprint $table) {
                $table->dropColumn('verified_by');
            });
        }

        if (Schema::hasTable('tb_punia_pendatang') && Schema::hasColumn('tb_punia_pendatang', 'verified_by')) {
            Schema::table('tb_punia_pendatang', function (Blueprint $table) {
                $table->dropColumn('verified_by');
            });
        }
    }
};