<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_tiket_detail', function (Blueprint $table) {
            $table->id('id_tiket_detail');
            $table->unsignedBigInteger('id_tiket');
            $table->unsignedBigInteger('id_kategori_tiket');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->foreign('id_tiket')
                  ->references('id_tiket')
                  ->on('tb_tiket_wisata')
                  ->onDelete('cascade');
                  
            $table->foreign('id_kategori_tiket')
                  ->references('id_kategori_tiket')
                  ->on('tb_kategori_tiket')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tiket_detail');
    }
};
