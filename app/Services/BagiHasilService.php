<?php

namespace App\Services;

use App\Models\PengaturanBagiHasil;
use App\Models\RiwayatBagiHasil;
use App\Models\SaldoKas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BagiHasilService
{
    /**
     * Split a payment into Desa and Banjar portions.
     * Called when a payment status becomes 'completed'/'lunas'.
     *
     * @param string $jenisPunia 'usaha' or 'tamiu'
     * @param int    $idPembayaran ID of the payment record
     * @param int    $idDataBanjar Banjar where the payer belongs
     * @param float  $nominal Total payment amount
     * @param string $metodePembayaran 'cash', 'online', 'qris', 'xendit', etc.
     * @param string|null $tanggal Payment date
     * @return RiwayatBagiHasil|null
     */
    public static function splitPayment($jenisPunia, $idPembayaran, $idDataBanjar, $nominal, $metodePembayaran = 'cash', $tanggal = null)
    {
        // Don't process if no banjar
        if (!$idDataBanjar) {
            Log::warning("BagiHasilService: No banjar for payment {$jenisPunia}#{$idPembayaran}");
            return null;
        }

        // Check if already split (prevent duplicates)
        $existing = RiwayatBagiHasil::where('jenis_punia', $jenisPunia)
            ->where('id_pembayaran', $idPembayaran)
            ->where('aktif', 1)
            ->first();

        if ($existing) {
            Log::info("BagiHasilService: Already split {$jenisPunia}#{$idPembayaran}");
            return $existing;
        }

        // Get percentage settings
        $pengaturan = PengaturanBagiHasil::getPersentase($jenisPunia, $idDataBanjar);

        if (!$pengaturan) {
            // No settings configured — default 100% to Desa
            $persenDesa = 100;
            $persenBanjar = 0;
        } else {
            $persenDesa = $pengaturan->persen_desa;
            $persenBanjar = $pengaturan->persen_banjar;
        }

        // Calculate amounts
        $nominalDesa = round($nominal * $persenDesa / 100, 2);
        $nominalBanjar = round($nominal * $persenBanjar / 100, 2);

        // Ensure no rounding loss — give remainder to desa
        $remainder = $nominal - $nominalDesa - $nominalBanjar;
        if ($remainder != 0) {
            $nominalDesa += $remainder;
        }

        // Normalize payment method
        $isOnline = in_array(strtolower($metodePembayaran), ['xendit', 'online', 'qris']);

        return DB::transaction(function () use (
            $jenisPunia, $idPembayaran, $idDataBanjar, $nominal,
            $persenDesa, $persenBanjar, $nominalDesa, $nominalBanjar,
            $metodePembayaran, $isOnline, $tanggal
        ) {
            // 1. Create riwayat record
            $riwayat = RiwayatBagiHasil::create([
                'jenis_punia' => $jenisPunia,
                'id_pembayaran' => $idPembayaran,
                'id_data_banjar' => $idDataBanjar,
                'nominal_total' => $nominal,
                'persen_desa' => $persenDesa,
                'persen_banjar' => $persenBanjar,
                'nominal_desa' => $nominalDesa,
                'nominal_banjar' => $nominalBanjar,
                'metode_pembayaran' => $metodePembayaran,
                'status_setor_desa' => 'pending',
                'status_setor_banjar' => 'pending',
                'tanggal' => $tanggal ?: now(),
            ]);

            // 2. Update saldo kas
            if ($isOnline) {
                // Online payment → goes to Desa's PG first
                // Desa saldo_online increases by full amount (it's in their PG)
                // Banjar portion is "owed" by desa to banjar
                $saldoDesa = SaldoKas::getOrCreate(null);
                $saldoDesa->increment('saldo_online', $nominal);
                $saldoDesa->increment('total_masuk', $nominal);
            } else {
                // Cash payment → goes to Banjar first (collected by penagih)
                // Banjar saldo_cash increases by full amount
                // Desa portion is "owed" by banjar to desa
                $saldoBanjar = SaldoKas::getOrCreate($idDataBanjar);
                $saldoBanjar->increment('saldo_cash', $nominal);
                $saldoBanjar->increment('total_masuk', $nominal);
            }

            Log::info("BagiHasilService: Split {$jenisPunia}#{$idPembayaran} — Desa: {$nominalDesa} ({$persenDesa}%), Banjar: {$nominalBanjar} ({$persenBanjar}%), Method: {$metodePembayaran}");

            return $riwayat;
        });
    }

    /**
     * Process "Banjar setor ke Desa" — transfer desa's share from banjar to desa.
     * Used for CASH flow: penagih collects → banjar holds → banjar sends desa's share.
     */
    public static function setorBanjarKeDesa($idDataBanjar, $nominal)
    {
        return DB::transaction(function () use ($idDataBanjar, $nominal) {
            $saldoBanjar = SaldoKas::getOrCreate($idDataBanjar);
            $saldoDesa = SaldoKas::getOrCreate(null);

            // Decrease banjar cash, increase desa cash
            $saldoBanjar->decrement('saldo_cash', $nominal);
            $saldoBanjar->increment('total_keluar', $nominal);
            $saldoDesa->increment('saldo_cash', $nominal);
            $saldoDesa->increment('total_masuk', $nominal);

            return true;
        });
    }

    /**
     * Process "Desa setor ke Banjar" — transfer banjar's share from desa to banjar.
     * Used for ONLINE flow: desa withdraws from PG → sends banjar's share.
     */
    public static function setorDesaKeBanjar($idDataBanjar, $nominal)
    {
        return DB::transaction(function () use ($idDataBanjar, $nominal) {
            $saldoDesa = SaldoKas::getOrCreate(null);
            $saldoBanjar = SaldoKas::getOrCreate($idDataBanjar);

            // Decrease desa online, increase banjar online
            $saldoDesa->decrement('saldo_online', $nominal);
            $saldoDesa->increment('total_keluar', $nominal);
            $saldoBanjar->increment('saldo_online', $nominal);
            $saldoBanjar->increment('total_masuk', $nominal);

            return true;
        });
    }

    /**
     * Process "Desa tarik dari Payment Gateway" — desa withdraws from PG to bank.
     */
    public static function desaTarikPG($nominal)
    {
        return DB::transaction(function () use ($nominal) {
            $saldoDesa = SaldoKas::getOrCreate(null);

            // Convert online to cash (withdrawn from PG to bank account)
            $saldoDesa->decrement('saldo_online', $nominal);
            $saldoDesa->increment('saldo_cash', $nominal);

            return true;
        });
    }

    /**
     * Get summary of what banjar owes to desa (from cash collections).
     */
    public static function getHutangBanjarKeDesa($idDataBanjar)
    {
        $totalCashCollected = RiwayatBagiHasil::where('id_data_banjar', $idDataBanjar)
            ->where('aktif', 1)
            ->where('metode_pembayaran', 'cash')
            ->sum('nominal_desa');

        $totalSudahSetor = RiwayatBagiHasil::where('id_data_banjar', $idDataBanjar)
            ->where('aktif', 1)
            ->where('metode_pembayaran', 'cash')
            ->where('status_setor_desa', 'selesai')
            ->sum('nominal_desa');

        return $totalCashCollected - $totalSudahSetor;
    }

    /**
     * Get summary of what desa owes to banjar (from online payments).
     */
    public static function getHutangDesaKeBanjar($idDataBanjar)
    {
        $totalOnlineReceived = RiwayatBagiHasil::where('id_data_banjar', $idDataBanjar)
            ->where('aktif', 1)
            ->whereIn('metode_pembayaran', ['xendit', 'online', 'qris'])
            ->sum('nominal_banjar');

        $totalSudahSetor = RiwayatBagiHasil::where('id_data_banjar', $idDataBanjar)
            ->where('aktif', 1)
            ->whereIn('metode_pembayaran', ['xendit', 'online', 'qris'])
            ->where('status_setor_banjar', 'selesai')
            ->sum('nominal_banjar');

        return $totalOnlineReceived - $totalSudahSetor;
    }
}
