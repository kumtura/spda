<?php

namespace App\Http\Controllers;

use App\Models\Danapunia;
use App\Models\Usaha;
use Illuminate\Http\Request;

class PuniaBayarController extends Controller
{
    public function show(Request $request, $id_usaha)
    {
        $bulan = (int) $request->query('bulan');
        $tahun = (int) $request->query('tahun');

        if (!$bulan || $bulan < 1 || $bulan > 12 || !$tahun) {
            abort(404);
        }

        $usaha = Usaha::join('tb_detail_usaha', 'tb_detail_usaha.id_usaha', 'tb_usaha.id_usaha')
            ->leftJoin('tb_penanggung_jawab', 'tb_penanggung_jawab.id_usaha', 'tb_usaha.id_usaha')
            ->where('tb_usaha.id_usaha', $id_usaha)
            ->where('tb_usaha.aktif', '1')
            ->select('tb_usaha.id_usaha', 'tb_usaha.nama_usaha', 'tb_detail_usaha.minimal_bayar',
                     'tb_penanggung_jawab.nama as nama_pj', 'tb_penanggung_jawab.no_wa as no_wa_pj')
            ->first();

        if (!$usaha) {
            abort(404);
        }

        // Check if already paid
        $awal = $tahun . "-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . "-01";
        $akhir = $tahun . "-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . "-31";
        $existing = Danapunia::where('id_usaha', $id_usaha)
            ->where('tanggal_pembayaran', '>=', $awal)
            ->where('tanggal_pembayaran', '<=', $akhir)
            ->where('aktif', '1')
            ->first();

        $months_id = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $months_en = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('frontend.punia_payment', compact(
            'usaha', 'bulan', 'tahun', 'existing', 'months_id', 'months_en'
        ));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_usaha' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'jumlah_dana' => 'required|numeric|min:1000',
            'tanggal_pembayaran' => 'required|date',
            'bukti_pembayaran' => 'required|image|max:5120',
        ]);

        // Check if already paid
        $awal = $request->tahun . "-" . str_pad($request->bulan, 2, '0', STR_PAD_LEFT) . "-01";
        $akhir = $request->tahun . "-" . str_pad($request->bulan, 2, '0', STR_PAD_LEFT) . "-31";
        $existing = Danapunia::where('id_usaha', $request->id_usaha)
            ->where('tanggal_pembayaran', '>=', $awal)
            ->where('tanggal_pembayaran', '<=', $akhir)
            ->where('aktif', '1')
            ->first();

        if ($existing) {
            return back()->with('error', 'Pembayaran bulan ini sudah tercatat.');
        }

        $file = $request->file('bukti_pembayaran');
        $buktiFile = 'usaha_' . time() . '_' . $request->id_usaha . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('bukti_pembayaran'), $buktiFile);

        Danapunia::create([
            'id_usaha' => $request->id_usaha,
            'jumlah_dana' => $request->jumlah_dana,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'bulan_punia' => $request->bulan,
            'tahun_punia' => $request->tahun,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'metode_pembayaran' => 'transfer',
            'metode' => 'transfer',
            'bukti_pembayaran' => $buktiFile,
            'status_pembayaran' => 'pending',
            'status_verifikasi' => 'pending',
            'aktif' => '1',
        ]);

        return back()->with('success', '1');
    }
}
