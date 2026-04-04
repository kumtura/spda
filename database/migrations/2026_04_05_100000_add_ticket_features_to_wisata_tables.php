<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->integer('batas_tiket_harian')->nullable()->after('kapasitas_harian');
            $table->text('detail_termasuk')->nullable()->after('deskripsi');
            $table->text('cara_penggunaan')->nullable()->after('detail_termasuk');
            $table->text('pembatalan')->nullable()->after('cara_penggunaan');
            $table->text('syarat_ketentuan')->nullable()->after('pembatalan');
        });

        Schema::table('tb_kategori_tiket', function (Blueprint $table) {
            $table->enum('market_type', ['all', 'wna', 'local'])->default('all')->after('tipe_kategori');
        });
    }

    public function down(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->dropColumn(['batas_tiket_harian', 'detail_termasuk', 'cara_penggunaan', 'pembatalan', 'syarat_ketentuan']);
        });

        Schema::table('tb_kategori_tiket', function (Blueprint $table) {
            $table->dropColumn('market_type');
        });
    }
};
