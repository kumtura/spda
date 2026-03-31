<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_acara_punia', function (Blueprint $table) {
            $table->id('id_acara_punia');
            $table->string('nama_acara', 200);
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_acara')->nullable();
            $table->date('batas_pembayaran')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->unsignedInteger('created_by')->nullable();
            $table->char('aktif', 1)->default('1');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_acara_punia');
    }
};
