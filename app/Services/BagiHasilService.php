<?php

namespace App\Services;

use App\Models\Danapunia;
use App\Models\PuniaPendatang;
use App\Models\PengaturanBagiHasil;
use App\Models\RiwayatBagiHasil;
use App\Models\SaldoKas;
use App\Models\SetorPunia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BagiHasilService
{
    public static function synchronizeHistoricalData(): array
    {
        if (!Schema::hasTable('tb_riwayat_bagi_hasil') || !Schema::hasTable('tb_saldo_kas')) {
            return [
                'tamiu_synced' => 0,
                'usaha_synced' => 0,
            ];
        }

        $tamiuSynced = self::syncHistoricalTamiuPayments();
        $usahaSynced = self::syncHistoricalUsahaPayments();

        self::syncSettlementStatuses();
        self::rebuildSaldoSnapshots();

        return [
            'tamiu_synced' => $tamiuSynced,
            'usaha_synced' => $usahaSynced,
        ];
    }

    protected static function syncHistoricalTamiuPayments(): int
    {
        $synced = 0;

        $existingIds = RiwayatBagiHasil::where('aktif', 1)
            ->where('jenis_punia', 'tamiu')
            ->pluck('id_pembayaran')
            ->all();

        PuniaPendatang::with('pendatang:id_pendatang,id_data_banjar')
            ->where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where('nominal', '>', 0)
            ->when(!empty($existingIds), function ($query) use ($existingIds) {
                $query->whereNotIn('id_punia_pendatang', $existingIds);
            })
            ->orderBy('id_punia_pendatang')
            ->chunkById(100, function ($payments) use (&$synced) {
                foreach ($payments as $payment) {
                    $banjarId = $payment->pendatang->id_data_banjar ?? null;

                    if (!$banjarId) {
                        continue;
                    }

                    $riwayat = self::splitPayment(
                        'tamiu',
                        $payment->id_punia_pendatang,
                        $banjarId,
                        (float) $payment->nominal,
                        self::normalizePaymentMethod($payment->metode_pembayaran),
                        optional($payment->tanggal_bayar)->toDateTimeString() ?: optional($payment->created_at)->toDateTimeString()
                    );

                    if ($riwayat) {
                        $synced++;
                    }
                }
            }, 'id_punia_pendatang');

        return $synced;
    }

    protected static function syncHistoricalUsahaPayments(): int
    {
        $synced = 0;

        $existingIds = RiwayatBagiHasil::where('aktif', 1)
            ->where('jenis_punia', 'usaha')
            ->pluck('id_pembayaran')
            ->all();

        Danapunia::with('usaha.detail:id_detail_usaha,id_banjar')
            ->where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where('jumlah_dana', '>', 0)
            ->when(!empty($existingIds), function ($query) use ($existingIds) {
                $query->whereNotIn('id_dana_punia', $existingIds);
            })
            ->orderBy('id_dana_punia')
            ->chunkById(100, function ($payments) use (&$synced) {
                foreach ($payments as $payment) {
                    $banjarId = $payment->usaha?->detail?->id_banjar;

                    if (!$banjarId) {
                        continue;
                    }

                    $riwayat = self::splitPayment(
                        'usaha',
                        $payment->id_dana_punia,
                        $banjarId,
                        (float) $payment->jumlah_dana,
                        self::normalizePaymentMethod($payment->metode_pembayaran ?: $payment->metode),
                        $payment->tanggal_pembayaran ?: optional($payment->created_at)->toDateTimeString()
                    );

                    if ($riwayat) {
                        $synced++;
                    }
                }
            }, 'id_dana_punia');

        return $synced;
    }

    protected static function normalizePaymentMethod(?string $method): string
    {
        $value = strtolower(trim((string) $method));

        return match ($value) {
            '', 'tunai' => 'cash',
            'cash' => 'cash',
            'qris' => 'qris',
            'online', 'xendit' => 'xendit',
            default => $value,
        };
    }

    protected static function syncSettlementStatuses(): void
    {
        RiwayatBagiHasil::where('aktif', 1)->update([
            'status_setor_desa' => 'pending',
            'status_setor_banjar' => 'pending',
        ]);

        SetorPunia::where('aktif', 1)
            ->where('status', 'diterima')
            ->where('jenis_alur', 'banjar_ke_desa')
            ->orderBy('tanggal_setor')
            ->orderBy('id_setor_punia')
            ->get()
            ->each(function ($setor) {
                self::allocateSettlementToRiwayat(
                    $setor->id_data_banjar,
                    ['cash'],
                    'nominal_desa',
                    'status_setor_desa',
                    (float) $setor->nominal
                );
            });

        SetorPunia::where('aktif', 1)
            ->where('status', 'diterima')
            ->where('jenis_alur', 'desa_ke_banjar')
            ->whereNotNull('id_data_banjar_tujuan')
            ->orderBy('tanggal_setor')
            ->orderBy('id_setor_punia')
            ->get()
            ->each(function ($setor) {
                self::allocateSettlementToRiwayat(
                    $setor->id_data_banjar_tujuan,
                    ['xendit', 'online', 'qris'],
                    'nominal_banjar',
                    'status_setor_banjar',
                    (float) $setor->nominal
                );
            });
    }

    protected static function allocateSettlementToRiwayat(?int $banjarId, array $methods, string $nominalColumn, string $statusColumn, float $nominal): void
    {
        if (!$banjarId || $nominal <= 0) {
            return;
        }

        $remaining = round($nominal, 2);

        $rows = RiwayatBagiHasil::where('aktif', 1)
            ->where('id_data_banjar', $banjarId)
            ->whereIn('metode_pembayaran', $methods)
            ->where($statusColumn, 'pending')
            ->orderBy('tanggal')
            ->orderBy('id_riwayat')
            ->get();

        foreach ($rows as $row) {
            $portion = round((float) $row->{$nominalColumn}, 2);

            if ($portion <= 0 || $remaining < $portion) {
                continue;
            }

            $row->update([$statusColumn => 'selesai']);
            $remaining = round($remaining - $portion, 2);

            if ($remaining <= 0) {
                break;
            }
        }
    }

    protected static function rebuildSaldoSnapshots(): void
    {
        SaldoKas::query()->update([
            'saldo_cash' => 0,
            'saldo_online' => 0,
            'total_masuk' => 0,
            'total_keluar' => 0,
        ]);

        $snapshots = [
            'desa' => [
                'saldo_cash' => 0,
                'saldo_online' => 0,
                'total_masuk' => 0,
                'total_keluar' => 0,
            ],
        ];

        RiwayatBagiHasil::where('aktif', 1)
            ->orderBy('id_riwayat')
            ->chunkById(200, function ($rows) use (&$snapshots) {
                foreach ($rows as $row) {
                    $method = strtolower((string) $row->metode_pembayaran);
                    $isOnline = in_array($method, ['xendit', 'online', 'qris'], true);

                    if ($isOnline) {
                        $snapshots['desa']['saldo_online'] += (float) $row->nominal_total;
                        $snapshots['desa']['total_masuk'] += (float) $row->nominal_total;
                        continue;
                    }

                    $key = 'banjar_' . $row->id_data_banjar;
                    if (!isset($snapshots[$key])) {
                        $snapshots[$key] = [
                            'id_data_banjar' => $row->id_data_banjar,
                            'saldo_cash' => 0,
                            'saldo_online' => 0,
                            'total_masuk' => 0,
                            'total_keluar' => 0,
                        ];
                    }

                    $snapshots[$key]['saldo_cash'] += (float) $row->nominal_total;
                    $snapshots[$key]['total_masuk'] += (float) $row->nominal_total;
                }
            }, 'id_riwayat');

        SetorPunia::where('aktif', 1)
            ->where('status', 'diterima')
            ->orderBy('id_setor_punia')
            ->chunkById(200, function ($rows) use (&$snapshots) {
                foreach ($rows as $row) {
                    $nominal = (float) $row->nominal;
                    switch ($row->jenis_alur) {
                        case 'banjar_ke_desa':
                            $banjarKey = 'banjar_' . $row->id_data_banjar;
                            if (!isset($snapshots[$banjarKey])) {
                                $snapshots[$banjarKey] = [
                                    'id_data_banjar' => $row->id_data_banjar,
                                    'saldo_cash' => 0,
                                    'saldo_online' => 0,
                                    'total_masuk' => 0,
                                    'total_keluar' => 0,
                                ];
                            }
                            $snapshots[$banjarKey]['saldo_cash'] -= $nominal;
                            $snapshots[$banjarKey]['total_keluar'] += $nominal;
                            $snapshots['desa']['saldo_cash'] += $nominal;
                            $snapshots['desa']['total_masuk'] += $nominal;
                            break;

                        case 'desa_tarik_pg':
                            $snapshots['desa']['saldo_online'] -= $nominal;
                            $snapshots['desa']['saldo_cash'] += $nominal;
                            break;

                        case 'desa_ke_banjar':
                            if (!$row->id_data_banjar_tujuan) {
                                break;
                            }
                            $banjarKey = 'banjar_' . $row->id_data_banjar_tujuan;
                            if (!isset($snapshots[$banjarKey])) {
                                $snapshots[$banjarKey] = [
                                    'id_data_banjar' => $row->id_data_banjar_tujuan,
                                    'saldo_cash' => 0,
                                    'saldo_online' => 0,
                                    'total_masuk' => 0,
                                    'total_keluar' => 0,
                                ];
                            }
                            $snapshots['desa']['saldo_online'] -= $nominal;
                            $snapshots['desa']['total_keluar'] += $nominal;
                            $snapshots[$banjarKey]['saldo_online'] += $nominal;
                            $snapshots[$banjarKey]['total_masuk'] += $nominal;
                            break;
                    }
                }
            }, 'id_setor_punia');

        $desaSnapshot = $snapshots['desa'];
        $desaSaldo = SaldoKas::firstOrCreate(
            ['id_data_banjar' => null],
            ['saldo_cash' => 0, 'saldo_online' => 0, 'total_masuk' => 0, 'total_keluar' => 0]
        );
        $desaSaldo->update($desaSnapshot);

        foreach ($snapshots as $key => $snapshot) {
            if ($key === 'desa') {
                continue;
            }

            SaldoKas::updateOrCreate(
                ['id_data_banjar' => $snapshot['id_data_banjar']],
                [
                    'saldo_cash' => $snapshot['saldo_cash'],
                    'saldo_online' => $snapshot['saldo_online'],
                    'total_masuk' => $snapshot['total_masuk'],
                    'total_keluar' => $snapshot['total_keluar'],
                ]
            );
        }
    }

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

        $effectiveDate = $tanggal ?: now();

        // Get percentage settings based on the transaction date.
        $pengaturan = PengaturanBagiHasil::getPersentase($jenisPunia, $idDataBanjar, $effectiveDate);

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
            $metodePembayaran, $isOnline, $tanggal, $effectiveDate
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
                'tanggal' => $effectiveDate,
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
