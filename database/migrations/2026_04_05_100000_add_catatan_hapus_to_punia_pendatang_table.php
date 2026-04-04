<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_punia_pendatang', function (Blueprint $table) {
            $table->text('catatan_hapus')->nullable()->after('keterangan');
            $table->unsignedInteger('dihapus_oleh')->nullable()->after('catatan_hapus');
            $table->dateTime('tanggal_hapus')->nullable()->after('dihapus_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('tb_punia_pendatang', function (Blueprint $table) {
            $table->dropColumn(['catatan_hapus', 'dihapus_oleh', 'tanggal_hapus']);
        });
    }
};
