<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pengaturan_bagi_hasil', function (Blueprint $table) {
            $table->id('id_pengaturan');
            $table->enum('jenis_punia', ['usaha', 'tamiu']);
            $table->unsignedBigInteger('id_data_banjar')->nullable(); // NULL = global semua banjar
            $table->decimal('persen_desa', 5, 2)->default(100.00);
            $table->decimal('persen_banjar', 5, 2)->default(0.00);
            $table->date('berlaku_sejak');
            $table->string('keterangan', 500)->nullable();
            $table->boolean('aktif')->default(1);
            $table->timestamps();

            $table->foreign('id_data_banjar')->references('id_data_banjar')->on('tb_data_banjar')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pengaturan_bagi_hasil');
    }
};
