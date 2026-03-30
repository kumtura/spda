<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_objek_wisata')) {
            Schema::create('tb_objek_wisata', function (Blueprint $table) {
                $table->id('id_objek_wisata');
                $table->string('nama_objek');
                $table->text('deskripsi')->nullable();
                $table->text('alamat')->nullable();
                $table->string('foto')->nullable();
                $table->decimal('harga_tiket', 15, 2)->default(0);
                $table->integer('kapasitas_harian')->default(0);
                $table->time('jam_buka')->nullable();
                $table->time('jam_tutup')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->enum('aktif', ['0', '1'])->default('1');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_objek_wisata');
    }
};
