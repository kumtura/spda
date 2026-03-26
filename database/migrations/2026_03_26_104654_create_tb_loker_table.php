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
        Schema::create('tb_loker', function (Blueprint $table) {
            $table->id('id_loker');
            $table->unsignedBigInteger('id_usaha'); // references tb_usaha
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('status', ['Buka', 'Tutup'])->default('Buka');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_loker');
    }
};
