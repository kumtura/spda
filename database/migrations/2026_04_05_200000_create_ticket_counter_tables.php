<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Assignment petugas ticket counter ke objek wisata
        if (!Schema::hasTable('tb_ticket_counter_assignment')) {
            Schema::create('tb_ticket_counter_assignment', function (Blueprint $table) {
                $table->id('id_assignment');
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('id_objek_wisata');
                $table->enum('aktif', ['0', '1'])->default('1');
                $table->timestamps();

                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('id_objek_wisata')->references('id_objek_wisata')->on('tb_objek_wisata')->onDelete('cascade');
            });
        }

        // Absensi petugas ticket counter
        if (!Schema::hasTable('tb_absensi_counter')) {
            Schema::create('tb_absensi_counter', function (Blueprint $table) {
                $table->id('id_absensi');
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('id_objek_wisata');
                $table->datetime('waktu_masuk');
                $table->datetime('waktu_keluar')->nullable();
                $table->string('foto_masuk')->nullable();
                $table->string('foto_keluar')->nullable();
                $table->string('lokasi_masuk')->nullable();
                $table->string('lokasi_keluar')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('id_objek_wisata')->references('id_objek_wisata')->on('tb_objek_wisata')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_absensi_counter');
        Schema::dropIfExists('tb_ticket_counter_assignment');
    }
};
