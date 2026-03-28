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
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_dana_punia', 'xendit_id')) {
                $table->string('xendit_id')->nullable()->after('metode');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('pending')->after('xendit_id');
            }
            if (!Schema::hasColumn('tb_dana_punia', 'payment_data')) {
                $table->json('payment_data')->nullable()->after('status_pembayaran');
            }
        });

        Schema::table('tb_sumbangan_sukarela', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_sumbangan_sukarela', 'xendit_id')) {
                $table->string('xendit_id')->nullable()->after('metode');
            }
            if (!Schema::hasColumn('tb_sumbangan_sukarela', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('pending')->after('xendit_id');
            }
            if (!Schema::hasColumn('tb_sumbangan_sukarela', 'payment_data')) {
                $table->json('payment_data')->nullable()->after('status_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dana_punia', function (Blueprint $table) {
            $columns = ['xendit_id', 'status_pembayaran', 'payment_data'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tb_dana_punia', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('tb_sumbangan_sukarela', function (Blueprint $table) {
            $columns = ['xendit_id', 'status_pembayaran', 'payment_data'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tb_sumbangan_sukarela', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
