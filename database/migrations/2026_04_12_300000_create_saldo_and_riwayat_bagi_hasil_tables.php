<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Saldo Kas per Banjar dan Desa
        Schema::create('tb_saldo_kas', function (Blueprint $table) {
            $table->id('id_saldo_kas');
            $table->unsignedBigInteger('id_data_banjar')->nullable(); // NULL = Kas Desa Adat
            $table->decimal('saldo_cash', 15, 2)->default(0);
            $table->decimal('saldo_online', 15, 2)->default(0);
            $table->decimal('total_masuk', 15, 2)->default(0);
            $table->decimal('total_keluar', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_data_banjar')->references('id_data_banjar')->on('tb_data_banjar')->nullOnDelete();
            $table->unique('id_data_banjar'); // satu record per banjar + 1 untuk desa
        });

        // Riwayat Bagi Hasil — log setiap pembayaran yang di-split
        Schema::create('tb_riwayat_bagi_hasil', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->enum('jenis_punia', ['usaha', 'tamiu']);
            $table->unsignedBigInteger('id_pembayaran'); // id dari tb_dana_punia / tb_punia_pendatang
            $table->unsignedBigInteger('id_data_banjar');
            $table->decimal('nominal_total', 15, 2);
            $table->decimal('persen_desa', 5, 2);
            $table->decimal('persen_banjar', 5, 2);
            $table->decimal('nominal_desa', 15, 2);
            $table->decimal('nominal_banjar', 15, 2);
            $table->string('metode_pembayaran', 50)->nullable(); // cash/online/qris
            $table->enum('status_setor_desa', ['pending', 'selesai'])->default('pending');
            $table->enum('status_setor_banjar', ['pending', 'selesai'])->default('pending');
            $table->dateTime('tanggal');
            $table->boolean('aktif')->default(1);
            $table->timestamps();

            $table->foreign('id_data_banjar')->references('id_data_banjar')->on('tb_data_banjar');
        });

        // Modifikasi tb_setor_punia — tambah kolom alur dan tanda tangan
        Schema::table('tb_setor_punia', function (Blueprint $table) {
            $table->enum('jenis_alur', [
                'penagih_ke_banjar',   // Penagih setor cash ke kas banjar
                'banjar_ke_desa',      // Banjar setor bagian desa dari cash
                'desa_tarik_pg',       // Desa tarik dari payment gateway
                'desa_ke_banjar'       // Desa setor bagian banjar dari online
            ])->nullable()->after('jenis_setor');
            $table->string('nama_penyerah', 255)->nullable()->after('penerima');
            $table->string('jabatan_penyerah', 255)->nullable()->after('nama_penyerah');
            $table->string('nama_penerima_ttd', 255)->nullable()->after('jabatan_penyerah');
            $table->string('jabatan_penerima', 255)->nullable()->after('nama_penerima_ttd');
            $table->string('tanda_tangan', 255)->nullable()->after('jabatan_penerima'); // file path ttd
            $table->unsignedBigInteger('id_data_banjar_tujuan')->nullable()->after('tanda_tangan');
        });
    }

    public function down(): void
    {
        Schema::table('tb_setor_punia', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_alur', 'nama_penyerah', 'jabatan_penyerah',
                'nama_penerima_ttd', 'jabatan_penerima', 'tanda_tangan',
                'id_data_banjar_tujuan'
            ]);
        });
        Schema::dropIfExists('tb_riwayat_bagi_hasil');
        Schema::dropIfExists('tb_saldo_kas');
    }
};
