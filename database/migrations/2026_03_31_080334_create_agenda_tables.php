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
        Schema::create('tb_kategori_agenda', function (Blueprint $table) {
            $table->increments('id_kategori_agenda');
            $table->string('nama_kategori');
            $table->text('keterangan')->nullable();
            $table->char('aktif', 1)->default('1');
            $table->timestamps();
        });

        Schema::create('tb_agenda', function (Blueprint $table) {
            $table->increments('id_agenda');
            $table->integer('id_kategori_agenda')->unsigned();
            $table->string('judul_agenda');
            $table->text('deskripsi_agenda');
            $table->date('tanggal_agenda');
            $table->time('waktu_agenda')->nullable();
            $table->string('lokasi_agenda');
            $table->string('foto_agenda')->nullable();
            $table->string('status_agenda'); // Draft, Publish
            $table->char('aktif', 1)->default('1');
            $table->timestamps();

            $table->foreign('id_kategori_agenda')->references('id_kategori_agenda')->on('tb_kategori_agenda')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_agenda');
        Schema::dropIfExists('tb_kategori_agenda');
    }
};
