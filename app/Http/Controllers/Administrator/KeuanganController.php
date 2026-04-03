<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Keuangan;
use App\Models\Sumbangan;
use App\Models\Danapunia;
use App\Models\PuniaPendatang;

class KeuanganController extends BaseController
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pemasukan');
        $dateAwal = $request->get('dateawal', '');
        $dateAkhir = $request->get('dateakhir', '');

        // === PEMASUKAN (Income) - aggregated from 3 sources ===
        $pemasukan = collect();

        // 1) Sumbangan Sukarela (lunas/approved)
        $qSumbangan = Sumbangan::where('aktif', '1');
        if ($dateAwal) $qSumbangan->where('tanggal', '>=', $dateAwal);
        if ($dateAkhir) $qSumbangan->where('tanggal', '<=', $dateAkhir);
        $sumbangan = $qSumbangan->orderBy('tanggal', 'desc')->get();

        foreach ($sumbangan as $s) {
            $nama = $s->nama ?: 'Anonim';
            $tipe = match($s->status_donatur) {
                '1', 1 => 'Anonim',
                '2', 2 => 'Donatur',
                '3', 3 => 'Investor',
                default => 'Lainnya'
            };
            $metode = 'Cash';
            if ($s->metode_pembayaran == 'xendit' || $s->metode_pembayaran == 'online') {
                $metode = 'Online';
            } elseif ($s->metode_pembayaran == 'transfer_manual') {
                $metode = 'Transfer';
            } elseif ($s->metode == '1' || $s->metode == 1) {
                $metode = 'Transfer';
            } elseif ($s->metode == '2' || $s->metode == 2) {
                $metode = 'Cash';
            }
            $pemasukan->push([
                'tanggal' => $s->tanggal,
                'sumber' => 'Sumbangan',
                'nama' => $nama,
                'keterangan' => $s->deskripsi ?: ('Sumbangan ' . $tipe),
                'metode' => $metode,
                'nominal' => $s->nominal,
            ]);
        }

        // 2) Dana Punia Usaha (completed payments)
        $qDanapunia = Danapunia::where('status_pembayaran', 'completed');
        if ($dateAwal) $qDanapunia->where('tanggal_pembayaran', '>=', $dateAwal);
        if ($dateAkhir) $qDanapunia->where('tanggal_pembayaran', '<=', $dateAkhir);
        $danapunia = $qDanapunia->orderBy('tanggal_pembayaran', 'desc')->get();

        foreach ($danapunia as $d) {
            $metode = 'Cash';
            if ($d->metode_pembayaran == 'xendit' || $d->metode_pembayaran == 'online') {
                $metode = 'Online';
            } elseif ($d->metode_pembayaran == 'transfer_manual') {
                $metode = 'Transfer';
            }
            $pemasukan->push([
                'tanggal' => $d->tanggal_pembayaran,
                'sumber' => 'Punia Usaha',
                'nama' => $d->nama_donatur ?: 'Usaha',
                'keterangan' => 'Punia wajib usaha',
                'metode' => $metode,
                'nominal' => $d->jumlah_dana,
            ]);
        }

        // 3) Punia Pendatang (lunas payments)
        $qPuniaPendatang = PuniaPendatang::with('pendatang')
            ->where('status_pembayaran', 'lunas');
        if ($dateAwal) $qPuniaPendatang->where('tanggal_bayar', '>=', $dateAwal);
        if ($dateAkhir) $qPuniaPendatang->where('tanggal_bayar', '<=', $dateAkhir);
        $puniaPendatang = $qPuniaPendatang->orderBy('tanggal_bayar', 'desc')->get();

        foreach ($puniaPendatang as $p) {
            $metode = 'Cash';
            if ($p->metode_pembayaran == 'xendit' || $p->metode_pembayaran == 'online') {
                $metode = 'Online';
            } elseif ($p->metode_pembayaran == 'transfer_manual') {
                $metode = 'Transfer';
            }
            $jenis = $p->jenis_punia == 'acara' ? 'Acara: ' . ($p->nama_acara ?: '-') : 'Rutin ' . ($p->bulan_tahun ?: '');
            $pemasukan->push([
                'tanggal' => $p->tanggal_bayar,
                'sumber' => 'Punia Pendatang',
                'nama' => optional($p->pendatang)->nama ?: '-',
                'keterangan' => $jenis,
                'metode' => $metode,
                'nominal' => $p->nominal,
            ]);
        }

        // Sort by date desc
        $pemasukan = $pemasukan->sortByDesc('tanggal')->values();

        // === PENGELUARAN (Expenses) ===
        $qPengeluaran = Keuangan::where('jenis', 'pengeluaran')->where('aktif', 1);
        if ($dateAwal) $qPengeluaran->where('tanggal', '>=', $dateAwal);
        if ($dateAkhir) $qPengeluaran->where('tanggal', '<=', $dateAkhir);
        $pengeluaran = $qPengeluaran->orderBy('tanggal', 'desc')->get();

        // === TARIK DANA (Withdrawals) ===
        $qTarik = Keuangan::where('jenis', 'tarik')->where('aktif', 1);
        if ($dateAwal) $qTarik->where('tanggal', '>=', $dateAwal);
        if ($dateAkhir) $qTarik->where('tanggal', '<=', $dateAkhir);
        $tarik = $qTarik->orderBy('tanggal', 'desc')->get();

        // === TOTALS ===
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $totalTarik = $tarik->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran - $totalTarik;

        // Income by method
        $pemasukanOnline = $pemasukan->where('metode', 'Online')->sum('nominal');
        $pemasukanTransfer = $pemasukan->where('metode', 'Transfer')->sum('nominal');
        $pemasukanCash = $pemasukan->where('metode', 'Cash')->sum('nominal');

        // Pre-format data for JSON (avoid closures in Blade @json)
        $jsonPemasukan = $pemasukan->map(function($row) {
            return [
                'tanggal' => $row['tanggal'],
                'tanggal_fmt' => \Carbon\Carbon::parse($row['tanggal'])->format('d M Y'),
                'sumber' => $row['sumber'],
                'nama' => $row['nama'],
                'keterangan' => $row['keterangan'],
                'metode' => $row['metode'],
                'nominal' => (float)$row['nominal'],
            ];
        })->values();

        $jsonPengeluaran = $pengeluaran->map(function($row) {
            return [
                'id_keuangan' => $row->id_keuangan,
                'tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d'),
                'kategori' => $row->kategori,
                'penerima' => $row->penerima,
                'keterangan' => $row->keterangan,
                'metode_pembayaran' => $row->metode_pembayaran,
                'nominal' => (float)$row->nominal,
            ];
        })->values();

        $jsonTarik = $tarik->map(function($row) {
            return [
                'id_keuangan' => $row->id_keuangan,
                'tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d'),
                'penerima' => $row->penerima,
                'nama_bank' => $row->nama_bank,
                'no_rekening' => $row->no_rekening,
                'keterangan' => $row->keterangan,
                'nominal' => (float)$row->nominal,
            ];
        })->values();

        return view('admin.pages.keuangan.index', compact(
            'tab', 'dateAwal', 'dateAkhir',
            'pemasukan', 'pengeluaran', 'tarik',
            'jsonPemasukan', 'jsonPengeluaran', 'jsonTarik',
            'totalPemasukan', 'totalPengeluaran', 'totalTarik', 'saldo',
            'pemasukanOnline', 'pemasukanTransfer', 'pemasukanCash'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pengeluaran,tarik',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:500',
        ]);

        $data = [
            'jenis' => $request->jenis,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'kategori' => $request->kategori,
            'metode_pembayaran' => $request->metode_pembayaran,
            'penerima' => $request->penerima,
            'no_rekening' => $request->no_rekening,
            'nama_bank' => $request->nama_bank,
            'tanggal' => $request->tanggal,
            'id_user' => Session::get('id'),
            'aktif' => 1,
        ];

        // Handle file upload
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('bukti_keuangan'), $filename);
            $data['bukti'] = $filename;
        }

        Keuangan::create($data);

        $tab = $request->jenis == 'tarik' ? 'tarik' : 'pengeluaran';
        return redirect('administrator/keuangan?tab=' . $tab)->with('success', 'Data berhasil disimpan.');
    }

    public function destroy(Request $request, $id)
    {
        $record = Keuangan::findOrFail($id);
        $record->update(['aktif' => 0]);

        $tab = $record->jenis == 'tarik' ? 'tarik' : 'pengeluaran';
        return redirect('administrator/keuangan?tab=' . $tab)->with('success', 'Data berhasil dihapus.');
    }
}
