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
use App\Models\SaldoKas;
use App\Models\RiwayatBagiHasil;
use App\Services\BagiHasilService;
use Illuminate\Support\Facades\Schema;

class SetorPuniaController extends BaseController
{
    public function index(Request $request)
    {
        $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        $filterBanjar = $request->get('banjar', '');
        $filterJenis = $request->get('jenis', '');

        // === SALDO KAS (defensive — tables may not exist yet) ===
        $hasSaldoTable = Schema::hasTable('tb_saldo_kas');
        $hasRiwayatTable = Schema::hasTable('tb_riwayat_bagi_hasil');

        if ($hasSaldoTable) {
            $saldoDesa = SaldoKas::getOrCreate(null);
        } else {
            $saldoDesa = (object)['saldo_cash' => 0, 'saldo_online' => 0, 'total_masuk' => 0, 'total_keluar' => 0, 'total_saldo' => 0];
        }

        $banjarSaldos = [];
        foreach ($banjarList as $b) {
            if ($hasSaldoTable) {
                $saldo = SaldoKas::getOrCreate($b->id_data_banjar);
            } else {
                $saldo = (object)['saldo_cash' => 0, 'saldo_online' => 0, 'total_masuk' => 0, 'total_keluar' => 0, 'total_saldo' => 0];
            }
            $hutangKeDesa = $hasRiwayatTable ? BagiHasilService::getHutangBanjarKeDesa($b->id_data_banjar) : 0;
            $hakDariBanjar = $hasRiwayatTable ? BagiHasilService::getHutangDesaKeBanjar($b->id_data_banjar) : 0;
            $banjarSaldos[] = [
                'banjar' => $b,
                'saldo' => $saldo,
                'hutang_ke_desa' => $hutangKeDesa,
                'hak_dari_desa' => $hakDariBanjar,
            ];
        }

        // Total saldo all banjars
        $totalSaldoBanjar = $hasSaldoTable
            ? SaldoKas::whereNotNull('id_data_banjar')->sum(DB::raw('saldo_cash + saldo_online'))
            : 0;

        // === TRACKING: Legacy cash/online totals (backward compat) ===
        $totalCashTamiu = PuniaPendatang::where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where('metode_pembayaran', 'cash')
            ->sum('nominal');

        $totalCashUsaha = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'cash')
                  ->orWhere('metode', 'Cash')
                  ->orWhere('metode', 'cash');
            })
            ->sum('jumlah_dana');

        $totalCashAll = $totalCashTamiu + $totalCashUsaha;

        $totalOnlineTamiu = PuniaPendatang::where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'qris')
                  ->orWhere('metode_pembayaran', 'online')
                  ->orWhere('metode_pembayaran', 'xendit');
            })
            ->sum('nominal');

        $totalOnlineUsaha = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'xendit')
                  ->orWhere('metode_pembayaran', 'online')
                  ->orWhere('metode_pembayaran', 'qris');
            })
            ->sum('jumlah_dana');

        $totalOnlineAll = $totalOnlineTamiu + $totalOnlineUsaha;

        // === RIWAYAT SETOR/TARIK ===
        $query = SetorPunia::with(['banjar', 'banjarTujuan', 'user', 'verifier'])
            ->where('aktif', 1);

        if ($filterBanjar) {
            $query->where(function($q) use ($filterBanjar) {
                $q->where('id_data_banjar', $filterBanjar)
                  ->orWhere('id_data_banjar_tujuan', $filterBanjar);
            });
        }
        if ($filterJenis) {
            $query->where('jenis_alur', $filterJenis);
        }

        $riwayat = $query->orderBy('tanggal_setor', 'desc')->get();

        // Summary totals
        $totalSetorDiterima = SetorPunia::where('aktif', 1)->where('status', 'diterima')->sum('nominal');
        $totalPending = SetorPunia::where('aktif', 1)->where('status', 'pending')->sum('nominal');

        return view('admin.pages.setor_punia.index', compact(
            'banjarList', 'filterBanjar', 'filterJenis',
            'saldoDesa', 'banjarSaldos', 'totalSaldoBanjar',
            'totalCashTamiu', 'totalCashUsaha', 'totalCashAll',
            'totalOnlineTamiu', 'totalOnlineUsaha', 'totalOnlineAll',
            'riwayat', 'totalSetorDiterima', 'totalPending'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_alur' => 'required|in:penagih_ke_banjar,banjar_ke_desa,desa_tarik_pg,desa_ke_banjar',
            'nominal' => 'required|numeric|min:1',
            'tanggal_setor' => 'required|date',
            'keterangan' => 'required|string|max:500',
        ]);

        $data = [
            'jenis_setor' => in_array($request->jenis_alur, ['penagih_ke_banjar', 'banjar_ke_desa']) ? 'setor_cash' : 'tarik_online',
            'jenis_alur' => $request->jenis_alur,
            'sumber_punia' => $request->sumber_punia ?? 'campuran',
            'id_data_banjar' => $request->id_data_banjar,
            'id_data_banjar_tujuan' => $request->id_data_banjar_tujuan,
            'nominal' => $request->nominal,
            'tanggal_setor' => $request->tanggal_setor,
            'keterangan' => $request->keterangan,
            'penerima' => $request->penerima,
            'nama_penyerah' => $request->nama_penyerah,
            'jabatan_penyerah' => $request->jabatan_penyerah,
            'nama_penerima_ttd' => $request->nama_penerima_ttd,
            'jabatan_penerima' => $request->jabatan_penerima,
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
            'status' => 'pending',
            'id_user' => Auth::id(),
            'aktif' => 1,
        ];

        // Handle bukti upload
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_setor_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('bukti_keuangan'), $filename);
            $data['bukti'] = $filename;
        }

        // Handle tanda tangan upload
        if ($request->hasFile('tanda_tangan')) {
            $file = $request->file('tanda_tangan');
            $filename = time() . '_ttd_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('bukti_keuangan'), $filename);
            $data['tanda_tangan'] = $filename;
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
        $setor->verified_by = Auth::id();
        $setor->verified_at = now();
        $setor->catatan_verifikasi = $request->catatan;

        // If accepted, process the saldo transfer
        if ($request->action === 'diterima' && $setor->jenis_alur) {
            switch ($setor->jenis_alur) {
                case 'penagih_ke_banjar':
                    // Cash already added to banjar saldo via BagiHasilService::splitPayment
                    // This is just confirming the deposit was received
                    break;
                case 'banjar_ke_desa':
                    BagiHasilService::setorBanjarKeDesa($setor->id_data_banjar, $setor->nominal);
                    // Mark riwayat bagi hasil records as settled for desa portion
                    RiwayatBagiHasil::where('id_data_banjar', $setor->id_data_banjar)
                        ->where('aktif', 1)
                        ->where('metode_pembayaran', 'cash')
                        ->where('status_setor_desa', 'pending')
                        ->update(['status_setor_desa' => 'selesai']);
                    break;
                case 'desa_tarik_pg':
                    BagiHasilService::desaTarikPG($setor->nominal);
                    break;
                case 'desa_ke_banjar':
                    if ($setor->id_data_banjar_tujuan) {
                        BagiHasilService::setorDesaKeBanjar($setor->id_data_banjar_tujuan, $setor->nominal);
                        // Mark riwayat bagi hasil records as settled for banjar portion
                        RiwayatBagiHasil::where('id_data_banjar', $setor->id_data_banjar_tujuan)
                            ->where('aktif', 1)
                            ->whereIn('metode_pembayaran', ['xendit', 'online', 'qris'])
                            ->where('status_setor_banjar', 'pending')
                            ->update(['status_setor_banjar' => 'selesai']);
                    }
                    break;
            }
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
