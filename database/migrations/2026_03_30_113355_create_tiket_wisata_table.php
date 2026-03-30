<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_tiket_wisata')) {
            Schema::create('tb_tiket_wisata', function (Blueprint $table) {
                $table->id('id_tiket');
                $table->string('kode_tiket')->unique();
                $table->unsignedBigInteger('id_objek_wisata');
                $table->string('nama_pengunjung');
                $table->string('email')->nullable();
                $table->string('no_telp')->nullable();
                $table->integer('jumlah_tiket')->default(1);
                $table->decimal('total_harga', 15, 2);
                $table->date('tanggal_kunjungan');
                $table->enum('metode_pembelian', ['online', 'offline'])->default('online');
                $table->enum('metode_pembayaran', ['xendit', 'transfer_manual', 'cash'])->nullable();
                $table->string('xendit_id')->nullable();
                $table->string('bukti_transfer')->nullable();
                $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->nullable();
                $table->text('catatan_verifikasi')->nullable();
                $table->enum('status_pembayaran', ['pending', 'completed', 'failed'])->default('pending');
                $table->enum('status_tiket', ['belum_digunakan', 'sudah_digunakan', 'expired'])->default('belum_digunakan');
                $table->timestamp('waktu_scan')->nullable();
                $table->unsignedBigInteger('petugas_scan')->nullable();
                $table->text('qr_code')->nullable();
                $table->enum('aktif', ['0', '1'])->default('1');
                $table->timestamps();
                
                $table->foreign('id_objek_wisata')->references('id_objek_wisata')->on('tb_objek_wisata')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tiket_wisata');
    }
};
