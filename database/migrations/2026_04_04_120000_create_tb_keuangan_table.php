<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_keuangan', function (Blueprint $table) {
            $table->id('id_keuangan');
            $table->enum('jenis', ['pengeluaran', 'tarik']); // income auto-fetched from other tables
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan')->nullable();
            $table->string('kategori')->nullable(); // e.g. operasional, pembangunan, dll
            $table->string('metode_pembayaran')->nullable(); // cash, transfer, online
            $table->string('penerima')->nullable(); // nama penerima dana
            $table->string('no_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('bukti')->nullable(); // file path for proof
            $table->unsignedBigInteger('id_user')->nullable(); // who recorded this
            $table->date('tanggal');
            $table->boolean('aktif')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_keuangan');
    }
};
