<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_kategori_punia', function (Blueprint $table) {
            $table->id('id_kategori_punia');
            $table->string('nama_kategori', 100);
            $table->string('ikon', 50)->nullable()->default('bi-wallet2');
            $table->text('deskripsi_singkat')->nullable();
            $table->enum('aktif', ['0','1'])->default('1');
            $table->timestamps();
        });

        Schema::create('tb_alokasi_punia', function (Blueprint $table) {
            $table->id('id_alokasi_punia');
            $table->unsignedBigInteger('id_kategori_punia');
            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->date('tanggal_alokasi');
            $table->enum('aktif', ['0','1'])->default('1');
            $table->timestamps();

            $table->foreign('id_kategori_punia')->references('id_kategori_punia')->on('tb_kategori_punia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_alokasi_punia');
        Schema::dropIfExists('tb_kategori_punia');
    }
};
