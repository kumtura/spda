<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Keuangan;
use App\Models\Danapunia;
use App\Models\Sumbangan;
use App\Models\PuniaPendatang;
use App\Models\TiketWisata;
use Illuminate\Support\Facades\DB;

class KeuanganApiController extends BaseApiController
{
    /**
     * GET /api/v1/keuangan/ringkasan
     * Ringkasan keuangan (pemasukan, pengeluaran, saldo)
     */
    public function ringkasan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Pemasukan dari 4 sumber
        $punia = Danapunia::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->sum('jumlah_dana');

        $donasi = Sumbangan::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->sum('nominal');

        $puniaPendatang = PuniaPendatang::where('status_pembayaran', 'lunas')
            ->where('aktif', 1)
            ->whereYear('created_at', $tahun)
            ->sum('nominal');

        $tiketWisata = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->sum('total_harga');

        $totalPemasukan = $punia + $donasi + $puniaPendatang + $tiketWisata;

        // Pengeluaran & tarik dana dari tb_keuangan
        $pengeluaran = Keuangan::where('jenis', 'pengeluaran')
            ->where('aktif', '1')
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        $tarikDana = Keuangan::where('jenis', 'tarik_dana')
            ->where('aktif', '1')
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');

        return $this->successResponse([
            'tahun' => $tahun,
            'pemasukan' => [
                'punia_usaha'     => (float) $punia,
                'donasi'          => (float) $donasi,
                'punia_pendatang' => (float) $puniaPendatang,
                'tiket_wisata'    => (float) $tiketWisata,
                'total'           => (float) $totalPemasukan,
            ],
            'pengeluaran' => [
                'operasional' => (float) $pengeluaran,
                'tarik_dana'  => (float) $tarikDana,
                'total'       => (float) ($pengeluaran + $tarikDana),
            ],
            'saldo' => (float) ($totalPemasukan - $pengeluaran - $tarikDana),
        ]);
    }

    /**
     * GET /api/v1/keuangan/pemasukan
     * Rincian pemasukan per bulan
     */
    public function pemasukan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $punia = Danapunia::where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->sum('jumlah_dana');

            $donasi = Sumbangan::where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->sum('nominal');

            $pp = PuniaPendatang::where('status_pembayaran', 'lunas')
                ->where('aktif', 1)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->sum('nominal');

            $tiket = TiketWisata::where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->sum('total_harga');

            $months[] = [
                'bulan'           => $m,
                'punia_usaha'     => (float) $punia,
                'donasi'          => (float) $donasi,
                'punia_pendatang' => (float) $pp,
                'tiket_wisata'    => (float) $tiket,
                'total'           => (float) ($punia + $donasi + $pp + $tiket),
            ];
        }

        return $this->successResponse([
            'tahun'    => $tahun,
            'per_bulan' => $months,
        ]);
    }
}
