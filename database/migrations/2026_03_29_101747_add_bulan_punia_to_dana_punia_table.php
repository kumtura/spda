<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_dana_punia', 'bulan_punia')) {
                $table->integer('bulan_punia')->nullable()->after('tanggal_pembayaran')->comment('Bulan yang dibayar (1-12)');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'tahun_punia')) {
                $table->integer('tahun_punia')->nullable()->after('bulan_punia')->comment('Tahun yang dibayar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            if (Schema::hasColumn('tb_dana_punia', 'bulan_punia')) {
                $table->dropColumn('bulan_punia');
            }
            if (Schema::hasColumn('tb_dana_punia', 'tahun_punia')) {
                $table->dropColumn('tahun_punia');
            }
        });
    }
};
