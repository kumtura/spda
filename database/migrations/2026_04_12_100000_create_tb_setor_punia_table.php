<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tb_setor_punia')) {
            return; // Table already exists, skip
        }

        Schema::create('tb_setor_punia', function (Blueprint $table) {
            $table->id('id_setor_punia');
            $table->unsignedBigInteger('id_keuangan')->nullable(); // relasi ke tb_keuangan saat diproses
            $table->enum('jenis_setor', ['setor_cash', 'tarik_online']); 
            // setor_cash = kelian menyetor uang cash yang dikumpulkan ke kas desa
            // tarik_online = penarikan dana dari payment gateway (Xendit) ke rekening desa
            $table->enum('sumber_punia', ['tamiu', 'usaha', 'campuran', 'umum'])->default('campuran');
            $table->unsignedBigInteger('id_data_banjar')->nullable(); // banjar asal (untuk setor cash kelian)
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_setor');
            $table->string('keterangan')->nullable();
            $table->string('penerima')->nullable(); // siapa yang menerima setoran
            $table->string('nama_bank')->nullable(); // untuk tarik online
            $table->string('no_rekening')->nullable(); // untuk tarik online
            $table->string('bukti')->nullable(); // foto bukti setor/tarik
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->unsignedBigInteger('id_user')->nullable(); // siapa yang mencatat
            $table->unsignedBigInteger('verified_by')->nullable(); // siapa yang verifikasi
            $table->timestamp('verified_at')->nullable();
            $table->boolean('aktif')->default(1);
            $table->timestamps();

            $table->foreign('id_keuangan')->references('id_keuangan')->on('tb_keuangan')->nullOnDelete();
            $table->foreign('id_data_banjar')->references('id_data_banjar')->on('tb_data_banjar')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_setor_punia');
    }
};
