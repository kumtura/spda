<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pendatang', function (Blueprint $table) {
            $table->id('id_pendatang');
            $table->string('nama', 100);
            $table->string('nik', 20)->unique();
            $table->string('asal', 200);
            $table->string('no_hp', 20);
            $table->text('alamat_tinggal')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->char('aktif', 1)->default('1');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pendatang');
    }
};
