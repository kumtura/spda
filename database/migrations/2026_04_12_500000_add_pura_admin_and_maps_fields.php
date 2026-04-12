<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add id_pura to users table for Admin Pura (level 6) assignment
        if (!Schema::hasColumn('users', 'id_pura')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('id_pura')->nullable()->after('id_banjar');
            });
        }

        // Add google_maps_url + pengurus fields to tb_pura
        Schema::table('tb_pura', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_pura', 'google_maps_url')) {
                $table->string('google_maps_url', 500)->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('tb_pura', 'no_telp_pemangku')) {
                $table->string('no_telp_pemangku', 20)->nullable()->after('nama_pemangku');
            }
            if (!Schema::hasColumn('tb_pura', 'id_banjar_ketua')) {
                $table->unsignedBigInteger('id_banjar_ketua')->nullable()->after('no_telp_ketua');
            }
            if (!Schema::hasColumn('tb_pura', 'banjar_ketua_manual')) {
                $table->string('banjar_ketua_manual', 150)->nullable()->after('id_banjar_ketua');
            }
            if (!Schema::hasColumn('tb_pura', 'id_banjar_pemangku')) {
                $table->unsignedBigInteger('id_banjar_pemangku')->nullable()->after('no_telp_pemangku');
            }
            if (!Schema::hasColumn('tb_pura', 'banjar_pemangku_manual')) {
                $table->string('banjar_pemangku_manual', 150)->nullable()->after('id_banjar_pemangku');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'id_pura')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('id_pura');
            });
        }

        Schema::table('tb_pura', function (Blueprint $table) {
            $cols = ['google_maps_url', 'no_telp_pemangku', 'id_banjar_ketua', 'banjar_ketua_manual', 'id_banjar_pemangku', 'banjar_pemangku_manual'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('tb_pura', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
