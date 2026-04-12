<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_pura')) {
            Schema::create('tb_pura', function (Blueprint $table) {
                $table->bigIncrements('id_pura');
                $table->string('nama_pura', 200);
                $table->text('lokasi')->nullable();
                $table->string('latitude', 50)->nullable();
                $table->string('longitude', 50)->nullable();
                $table->string('nama_ketua_pura', 150)->nullable();
                $table->string('no_telp_ketua', 20)->nullable();
                $table->unsignedBigInteger('id_data_banjar')->nullable();
                $table->string('nama_pemangku', 150)->nullable();
                $table->string('wuku_odalan', 100)->nullable();
                $table->date('odalan_terdekat')->nullable();
                $table->string('gambar_pura', 255)->nullable();
                $table->text('deskripsi')->nullable();
                $table->enum('aktif', ['0', '1'])->default('1');
                $table->timestamps();

                $table->foreign('id_data_banjar')
                      ->references('id_data_banjar')
                      ->on('tb_data_banjar')
                      ->onDelete('set null');
            });
        }

        if (!Schema::hasTable('tb_gallery_pura')) {
            Schema::create('tb_gallery_pura', function (Blueprint $table) {
                $table->bigIncrements('id_gallery_pura');
                $table->unsignedBigInteger('id_pura');
                $table->string('gambar', 255);
                $table->string('caption', 255)->nullable();
                $table->integer('urutan')->default(0);
                $table->enum('aktif', ['0', '1'])->default('1');
                $table->timestamps();

                $table->foreign('id_pura')
                      ->references('id_pura')
                      ->on('tb_pura')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tb_punia_pura')) {
            Schema::create('tb_punia_pura', function (Blueprint $table) {
                $table->bigIncrements('id_punia_pura');
                $table->unsignedBigInteger('id_pura');
                $table->string('nama_donatur', 200)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('no_wa', 20)->nullable();
                $table->boolean('is_anonymous')->default(false);
                $table->decimal('nominal', 15, 2)->default(0);
                $table->string('metode_pembayaran', 50)->nullable();
                $table->string('xendit_id', 255)->nullable();
                $table->text('payment_data')->nullable();
                $table->string('status_pembayaran', 30)->default('pending');
                $table->date('tanggal_pembayaran')->nullable();
                $table->string('keterangan', 255)->nullable();
                $table->string('bukti_transfer', 255)->nullable();
                $table->string('status_verifikasi', 30)->default('pending');
                $table->string('catatan_verifikasi', 255)->nullable();
                $table->decimal('charge', 15, 2)->default(0);
                $table->enum('aktif', ['0', '1'])->default('1');
                $table->timestamps();

                $table->foreign('id_pura')
                      ->references('id_pura')
                      ->on('tb_pura')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tb_qris_pura')) {
            Schema::create('tb_qris_pura', function (Blueprint $table) {
                $table->bigIncrements('id_qris_pura');
                $table->unsignedBigInteger('id_pura');
                $table->string('qris_content', 500)->nullable();
                $table->string('qris_image', 255)->nullable();
                $table->string('nmid', 100)->nullable();
                $table->string('merchant_name', 200)->nullable();
                $table->enum('is_active', ['0', '1'])->default('1');
                $table->timestamps();

                $table->foreign('id_pura')
                      ->references('id_pura')
                      ->on('tb_pura')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_qris_pura');
        Schema::dropIfExists('tb_punia_pura');
        Schema::dropIfExists('tb_gallery_pura');
        Schema::dropIfExists('tb_pura');
    }
};
