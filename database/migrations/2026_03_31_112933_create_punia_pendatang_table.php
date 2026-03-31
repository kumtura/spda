<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_punia_pendatang', function (Blueprint $table) {
            $table->id('id_punia_pendatang');
            $table->unsignedBigInteger('id_pendatang');
            $table->enum('jenis_punia', ['rutin', 'acara'])->comment('rutin: bulanan/tahunan, acara: untuk acara tertentu');
            $table->string('nama_acara', 200)->nullable()->comment('Diisi jika jenis_punia = acara');
            $table->enum('periode_rutin', ['bulanan', 'tahunan'])->nullable()->comment('Diisi jika jenis_punia = rutin');
            $table->string('bulan_tahun', 20)->nullable()->comment('Format: YYYY-MM untuk bulanan, YYYY untuk tahunan');
            $table->decimal('nominal', 15, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->enum('metode_pembayaran', ['cash', 'qris'])->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->unsignedInteger('petugas_id')->nullable()->comment('ID user kelian yang mencatat');
            $table->text('keterangan')->nullable();
            $table->char('aktif', 1)->default('1');
            $table->timestamps();
            
            $table->foreign('id_pendatang')->references('id_pendatang')->on('tb_pendatang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_punia_pendatang');
    }
};
