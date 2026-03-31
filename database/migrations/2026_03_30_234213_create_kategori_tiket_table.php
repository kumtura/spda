<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kategori_tiket', function (Blueprint $table) {
            $table->id('id_kategori_tiket');
            $table->unsignedBigInteger('id_objek_wisata');
            $table->string('nama_kategori', 100);
            $table->enum('tipe_kategori', ['orang', 'kendaraan', 'paket'])->default('orang');
            $table->decimal('harga', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->tinyInteger('aktif')->default(1);
            $table->timestamps();
            
            $table->foreign('id_objek_wisata')
                  ->references('id_objek_wisata')
                  ->on('tb_objek_wisata')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kategori_tiket');
    }
};
