<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\SetorPunia;
use App\Models\Keuangan;
use App\Models\Banjar;
use App\Models\Danapunia;
use App\Models\PuniaPendatang;

class SetorPuniaController extends BaseController
{
    public function index(Request $request)
    {
        $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        $filterBanjar = $request->get('banjar', '');
        $filterJenis = $request->get('jenis', '');

        // === TRACKING: Hitung kas yang belum disetor ===

        // Total cash dari Punia Pendatang (tamiu)
        $qCashTamiu = PuniaPendatang::where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where('metode_pembayaran', 'cash');
        $totalCashTamiu = (clone $qCashTamiu)->sum('nominal');

        // Total cash dari Punia Usaha
        $totalCashUsaha = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'cash')
                  ->orWhere('metode', 'Cash')
                  ->orWhere('metode', 'cash');
            })
            ->sum('jumlah_dana');

        $totalCashAll = $totalCashTamiu + $totalCashUsaha;

        // Total online/QRIS dari Punia Pendatang
        $totalOnlineTamiu = PuniaPendatang::where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'qris')
                  ->orWhere('metode_pembayaran', 'online')
                  ->orWhere('metode_pembayaran', 'xendit');
            })
            ->sum('nominal');

        // Total online dari Punia Usaha
        $totalOnlineUsaha = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'xendit')
                  ->orWhere('metode_pembayaran', 'online')
                  ->orWhere('metode_pembayaran', 'qris');
            })
            ->sum('jumlah_dana');

        $totalOnlineAll = $totalOnlineTamiu + $totalOnlineUsaha;

        // Total sudah disetor cash
        $totalSudahSetorCash = SetorPunia::where('aktif', 1)
            ->where('jenis_setor', 'setor_cash')
            ->where('status', 'diterima')
            ->sum('nominal');

        // Total sudah ditarik online
        $totalSudahTarikOnline = SetorPunia::where('aktif', 1)
            ->where('jenis_setor', 'tarik_online')
            ->where('status', 'diterima')
            ->sum('nominal');

        // Saldo belum disetor
        $cashBelumSetor = $totalCashAll - $totalSudahSetorCash;
        if ($cashBelumSetor < 0) $cashBelumSetor = 0;

        $onlineBelumTarik = $totalOnlineAll - $totalSudahTarikOnline;
        if ($onlineBelumTarik < 0) $onlineBelumTarik = 0;

        // === RIWAYAT SETOR/TARIK ===
        $query = SetorPunia::with(['banjar', 'user', 'verifier'])
            ->where('aktif', 1);

        if ($filterBanjar) {
            $query->where('id_data_banjar', $filterBanjar);
        }
        if ($filterJenis) {
            $query->where('jenis_setor', $filterJenis);
        }

        $riwayat = $query->orderBy('tanggal_setor', 'desc')->get();

        // Totals for display
        $totalSetorDiterima = SetorPunia::where('aktif', 1)->where('status', 'diterima')->sum('nominal');
        $totalPending = SetorPunia::where('aktif', 1)->where('status', 'pending')->sum('nominal');

        return view('admin.pages.setor_punia.index', compact(
            'banjarList', 'filterBanjar', 'filterJenis',
            'totalCashTamiu', 'totalCashUsaha', 'totalCashAll',
            'totalOnlineTamiu', 'totalOnlineUsaha', 'totalOnlineAll',
            'totalSudahSetorCash', 'totalSudahTarikOnline',
            'cashBelumSetor', 'onlineBelumTarik',
            'riwayat', 'totalSetorDiterima', 'totalPending'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_setor' => 'required|in:setor_cash,tarik_online',
            'nominal' => 'required|numeric|min:1',
            'tanggal_setor' => 'required|date',
            'keterangan' => 'required|string|max:500',
        ]);

        $data = [
            'jenis_setor' => $request->jenis_setor,
            'sumber_punia' => $request->sumber_punia ?? 'campuran',
            'id_data_banjar' => $request->id_data_banjar,
            'nominal' => $request->nominal,
            'tanggal_setor' => $request->tanggal_setor,
            'keterangan' => $request->keterangan,
            'penerima' => $request->penerima,
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
            'status' => 'pending',
            'id_user' => Session::get('id'),
            'aktif' => 1,
        ];

        // Handle file upload
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_setor_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('bukti_keuangan'), $filename);
            $data['bukti'] = $filename;
        }

        SetorPunia::create($data);

        return redirect('administrator/setor_punia')->with('success', 'Setoran berhasil dicatat. Menunggu verifikasi.');
    }

    public function verify(Request $request, $id)
    {
        $setor = SetorPunia::findOrFail($id);

        $request->validate([
            'action' => 'required|in:diterima,ditolak',
        ]);

        $setor->status = $request->action;
        $setor->verified_by = Session::get('id');
        $setor->verified_at = now();
        $setor->catatan_verifikasi = $request->catatan;

        // Jika diterima, buat record di tb_keuangan juga
        if ($request->action === 'diterima') {
            $jenisKeuangan = $setor->jenis_setor === 'setor_cash' ? 'pemasukan' : 'tarik';
            
            // For setor_cash: it's "pemasukan" to kas desa (cash deposited)
            // Actually, the original pemasukan is already tracked. This is an internal transfer.
            // Let's record it as a separate tracking without duplicating pemasukan.
            // We'll use jenis = 'setor_punia' in keuangan for audit trail
            
            // Actually, since tb_keuangan only has enum('pengeluaran','tarik'), 
            // and this is a "setor" (deposit), we should add 'setor_punia' to the enum
            // OR just keep the relation without creating keuangan record
            // Let's keep it simple - the SetorPunia table IS the tracking. 
            // We don't need to duplicate into tb_keuangan.
        }

        $setor->save();

        $msg = $request->action === 'diterima' ? 'Setoran diverifikasi dan diterima.' : 'Setoran ditolak.';
        return redirect('administrator/setor_punia')->with('success', $msg);
    }

    public function destroy($id)
    {
        $setor = SetorPunia::findOrFail($id);
        $setor->update(['aktif' => 0]);

        return redirect('administrator/setor_punia')->with('success', 'Record berhasil dihapus.');
    }
}
