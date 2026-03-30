<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_tiket_wisata', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable()->after('xendit_id');
            }
            if (!Schema::hasColumn('tb_tiket_wisata', 'status_verifikasi')) {
                $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->nullable()->after('bukti_transfer');
            }
            if (!Schema::hasColumn('tb_tiket_wisata', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_tiket_wisata', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'status_verifikasi', 'catatan_verifikasi']);
        });
    }
};
