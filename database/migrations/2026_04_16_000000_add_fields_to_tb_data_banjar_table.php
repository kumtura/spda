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
        Schema::table('tb_data_banjar', function (Blueprint $table) {
            // Gambar Banjar
            if (!Schema::hasColumn('tb_data_banjar', 'gambar_banjar')) {
                $table->string('gambar_banjar')->nullable()->after('alamat_banjar');
            }
            
            // Kelian Dinas Data
            if (!Schema::hasColumn('tb_data_banjar', 'nama_kelian_dinas')) {
                $table->string('nama_kelian_dinas')->nullable()->after('gambar_banjar');
            }
            if (!Schema::hasColumn('tb_data_banjar', 'alamat_kelian_dinas')) {
                $table->text('alamat_kelian_dinas')->nullable()->after('nama_kelian_dinas');
            }
            if (!Schema::hasColumn('tb_data_banjar', 'no_telp_kelian_dinas')) {
                $table->string('no_telp_kelian_dinas', 20)->nullable()->after('alamat_kelian_dinas');
            }
            
            // Kelian Adat Data (additional fields)
            if (!Schema::hasColumn('tb_data_banjar', 'alamat_kelian_adat')) {
                $table->text('alamat_kelian_adat')->nullable()->after('id_user_kelian');
            }
            if (!Schema::hasColumn('tb_data_banjar', 'no_telp_kelian_adat')) {
                $table->string('no_telp_kelian_adat', 20)->nullable()->after('alamat_kelian_adat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_data_banjar', function (Blueprint $table) {
            $table->dropColumnIfExists('gambar_banjar');
            $table->dropColumnIfExists('nama_kelian_dinas');
            $table->dropColumnIfExists('alamat_kelian_dinas');
            $table->dropColumnIfExists('no_telp_kelian_dinas');
            $table->dropColumnIfExists('alamat_kelian_adat');
            $table->dropColumnIfExists('no_telp_kelian_adat');
        });
    }
};
