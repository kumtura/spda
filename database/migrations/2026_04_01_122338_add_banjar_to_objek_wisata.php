<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->unsignedBigInteger('id_data_banjar')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('tb_objek_wisata', function (Blueprint $table) {
            $table->dropColumn('id_data_banjar');
        });
    }
};

