<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
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
use App\Models\User;
use App\Services\BagiHasilService;
use Illuminate\Support\Facades\Schema;

class SetorPuniaController extends BaseController
{
    public function index(Request $request)
    {
        if (Schema::hasTable('tb_riwayat_bagi_hasil') && Schema::hasTable('tb_saldo_kas')) {
            BagiHasilService::synchronizeHistoricalData();
        }

        $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        $filterBanjar = $request->get('banjar', '');
        $filterJenis = $request->get('jenis', '');
        $activeAlokasiTab = $request->get('tab', 'tamiu');

        if (!in_array($activeAlokasiTab, ['tamiu', 'usaha', 'hapus'], true)) {
            $activeAlokasiTab = 'tamiu';
        }

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

        $alokasiHistory = collect([]);
        if ($hasRiwayatTable) {
            $alokasiQuery = RiwayatBagiHasil::with('banjar')
                ->where('aktif', 1);

            if ($filterBanjar) {
                $alokasiQuery->where('id_data_banjar', $filterBanjar);
            }

            $alokasiRows = $alokasiQuery->orderBy('tanggal', 'desc')->orderBy('id_riwayat', 'desc')->get();

            $tamiuIds = $alokasiRows->where('jenis_punia', 'tamiu')->pluck('id_pembayaran')->unique()->values();
            $usahaIds = $alokasiRows->where('jenis_punia', 'usaha')->pluck('id_pembayaran')->unique()->values();

            $tamiuMap = PuniaPendatang::with('pendatang:id_pendatang,nama')
                ->whereIn('id_punia_pendatang', $tamiuIds)
                ->get()
                ->keyBy('id_punia_pendatang');

            $usahaMap = Danapunia::with('usaha.detail:id_detail_usaha,nama_usaha,id_banjar')
                ->whereIn('id_dana_punia', $usahaIds)
                ->get()
                ->keyBy('id_dana_punia');

            $alokasiHistory = $alokasiRows->map(function ($row) use ($tamiuMap, $usahaMap) {
                $source = null;
                $sourceName = '-';

                if ($row->jenis_punia === 'tamiu') {
                    $source = $tamiuMap->get($row->id_pembayaran);
                    $sourceName = $source?->pendatang?->nama ?: '-';
                } else {
                    $source = $usahaMap->get($row->id_pembayaran);
                    $sourceName = $source?->usaha?->detail?->nama_usaha ?: $source?->nama_donatur ?: '-';
                }

                $row->subjek_nama = $sourceName;
                $row->subjek_label = $row->jenis_punia === 'tamiu' ? 'Krama Tamiu' : 'Unit Usaha';
                $row->tanggal_transaksi = $source?->tanggal_bayar
                    ?: (!empty($source?->tanggal_pembayaran) ? Carbon::parse($source->tanggal_pembayaran) : $row->tanggal);
                $row->status_kolom_global = self::resolveGlobalStatusColumn($row->metode_pembayaran);
                $row->status_global = $row->{$row->status_kolom_global} ?? 'pending';
                $row->status_text = $row->status_kolom_global === 'status_setor_banjar'
                    ? ($row->status_global === 'selesai' ? 'Banjar Selesai' : 'Menunggu Setor Banjar')
                    : ($row->status_global === 'selesai' ? 'Desa Selesai' : 'Menunggu Setor Desa');

                return $row;
            });
        }

        $alokasiHistoryTamiu = $alokasiHistory->where('jenis_punia', 'tamiu')->values();
        $alokasiHistoryUsaha = $alokasiHistory->where('jenis_punia', 'usaha')->values();
        $riwayatHapus = $this->buildDeletedHistory($filterBanjar);

        // Summary totals
        $totalSetorDiterima = SetorPunia::where('aktif', 1)->where('status', 'diterima')->sum('nominal');
        $totalPending = SetorPunia::where('aktif', 1)->where('status', 'pending')->sum('nominal');

        return view('admin.pages.setor_punia.index', compact(
            'banjarList', 'filterBanjar', 'filterJenis',
            'saldoDesa', 'banjarSaldos', 'totalSaldoBanjar',
            'totalCashTamiu', 'totalCashUsaha', 'totalCashAll',
            'totalOnlineTamiu', 'totalOnlineUsaha', 'totalOnlineAll',
            'riwayat', 'alokasiHistory', 'alokasiHistoryTamiu', 'alokasiHistoryUsaha',
            'riwayatHapus', 'activeAlokasiTab', 'totalSetorDiterima', 'totalPending'
        ));
    }

    public function bulkUpdateAlokasiStatus(Request $request)
    {
        $validator = validator($request->all(), [
            'riwayat_ids' => 'required|array|min:1',
            'riwayat_ids.*' => 'integer',
            'jenis_punia_tab' => 'required|in:tamiu,usaha',
            'bulk_status_action' => 'required|in:follow_global_pending,follow_global_selesai',
        ]);

        if ($validator->fails()) {
            return $this->redirectToIndex($request)->with('error', $validator->errors()->first());
        }

        $targetStatus = $request->bulk_status_action === 'follow_global_selesai' ? 'selesai' : 'pending';

        $rows = RiwayatBagiHasil::where('aktif', 1)
            ->where('jenis_punia', $request->jenis_punia_tab)
            ->whereIn('id_riwayat', $request->riwayat_ids)
            ->get();

        if ($rows->isEmpty()) {
            return $this->redirectToIndex($request)->with('error', 'Tidak ada riwayat alokasi yang dipilih.');
        }

        $updated = 0;
        foreach ($rows as $row) {
            $statusColumn = self::resolveGlobalStatusColumn($row->metode_pembayaran);
            $overrideColumn = $statusColumn === 'status_setor_banjar'
                ? 'override_status_setor_banjar'
                : 'override_status_setor_desa';

            if (!$statusColumn) {
                continue;
            }

            $payload = [$statusColumn => $targetStatus];
            if (self::hasRiwayatOverrideColumn($overrideColumn)) {
                $payload[$overrideColumn] = true;
            }

            $row->update($payload);
            $updated++;
        }

        if ($updated === 0) {
            return $this->redirectToIndex($request)->with('error', 'Tidak ada status riwayat yang berhasil diperbarui.');
        }

        // Re-sync all data (split new payments, rebuild saldo) to reflect status changes
        BagiHasilService::synchronizeHistoricalData();

        $message = $targetStatus === 'selesai'
            ? 'Bulk edit berhasil. Status riwayat terpilih ditandai selesai mengikuti pembagian global.'
            : 'Bulk edit berhasil. Status riwayat terpilih dikembalikan ke menunggu mengikuti pembagian global.';

        return $this->redirectToIndex($request)->with('success', $message);
    }

    public function deleteAlokasi(Request $request)
    {
        $validator = validator($request->all(), [
            'id_riwayat' => 'required|integer|exists:tb_riwayat_bagi_hasil,id_riwayat',
            'catatan_hapus' => 'required|string|min:5|max:500',
            'tab' => 'nullable|in:tamiu,usaha,hapus',
        ], [
            'catatan_hapus.required' => 'Alasan penghapusan wajib diisi.',
            'catatan_hapus.min' => 'Alasan penghapusan minimal 5 karakter.',
        ]);

        if ($validator->fails()) {
            return $this->redirectToIndex($request)->with('error', $validator->errors()->first());
        }

        $riwayat = RiwayatBagiHasil::where('aktif', 1)->findOrFail($request->id_riwayat);

        DB::transaction(function () use ($riwayat, $request) {
            $source = $this->resolveSourceRecord($riwayat);

            if ($source) {
                $sourceUpdate = [
                    'aktif' => 0,
                ];

                if (self::sourceSupportsDeletionAudit($source)) {
                    $sourceUpdate['catatan_hapus'] = $request->catatan_hapus;
                    $sourceUpdate['dihapus_oleh'] = Auth::id();
                    $sourceUpdate['tanggal_hapus'] = now();
                }

                $source->update($sourceUpdate);
            }

            RiwayatBagiHasil::where('jenis_punia', $riwayat->jenis_punia)
                ->where('id_pembayaran', $riwayat->id_pembayaran)
                ->update(['aktif' => 0]);
        });

        if (Schema::hasTable('tb_riwayat_bagi_hasil') && Schema::hasTable('tb_saldo_kas')) {
            BagiHasilService::synchronizeHistoricalData();
        }

        return $this->redirectToIndex($request)->with('success', 'Transaksi punia berhasil dihapus dan tidak lagi masuk ke perhitungan keuangan.');
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

        $setor->save();

        if ($request->action === 'diterima' && Schema::hasTable('tb_riwayat_bagi_hasil') && Schema::hasTable('tb_saldo_kas')) {
            BagiHasilService::synchronizeHistoricalData();
        }

        $msg = $request->action === 'diterima' ? 'Setoran diverifikasi dan diterima.' : 'Setoran ditolak.';
        return redirect('administrator/setor_punia')->with('success', $msg);
    }

    public function destroy($id)
    {
        $setor = SetorPunia::findOrFail($id);
        $setor->update(['aktif' => 0]);

        return redirect('administrator/setor_punia')->with('success', 'Record berhasil dihapus.');
    }

    protected static function resolveGlobalStatusColumn(?string $metodePembayaran): string
    {
        $method = strtolower(trim((string) $metodePembayaran));

        return in_array($method, ['xendit', 'online', 'qris'], true)
            ? 'status_setor_banjar'
            : 'status_setor_desa';
    }

    protected static function hasRiwayatOverrideColumn(string $column): bool
    {
        return Schema::hasColumn('tb_riwayat_bagi_hasil', $column);
    }

    protected function resolveSourceRecord(RiwayatBagiHasil $riwayat)
    {
        return $riwayat->jenis_punia === 'tamiu'
            ? PuniaPendatang::find($riwayat->id_pembayaran)
            : Danapunia::find($riwayat->id_pembayaran);
    }

    protected static function sourceSupportsDeletionAudit($source): bool
    {
        $table = $source->getTable();

        return Schema::hasColumn($table, 'catatan_hapus')
            && Schema::hasColumn($table, 'dihapus_oleh')
            && Schema::hasColumn($table, 'tanggal_hapus');
    }

    protected function buildDeletedHistory($filterBanjar)
    {
        $deletedRiwayatRows = RiwayatBagiHasil::with('banjar')
            ->where('aktif', 0)
            ->when($filterBanjar, function ($query) use ($filterBanjar) {
                $query->where('id_data_banjar', $filterBanjar);
            })
            ->orderBy('updated_at', 'desc')
            ->orderBy('id_riwayat', 'desc')
            ->get();

        $tamiuIds = $deletedRiwayatRows->where('jenis_punia', 'tamiu')->pluck('id_pembayaran')->unique()->values();
        $usahaIds = $deletedRiwayatRows->where('jenis_punia', 'usaha')->pluck('id_pembayaran')->unique()->values();

        $deletedTamiuMap = PuniaPendatang::with('pendatang:id_pendatang,nama')
            ->whereIn('id_punia_pendatang', $tamiuIds)
            ->get()
            ->keyBy('id_punia_pendatang');

        $deletedUsahaMap = Danapunia::with('usaha.detail:id_detail_usaha,nama_usaha')
            ->whereIn('id_dana_punia', $usahaIds)
            ->get()
            ->keyBy('id_dana_punia');

        $userIds = collect()
            ->merge($deletedTamiuMap->pluck('dihapus_oleh'))
            ->merge($deletedUsahaMap->pluck('dihapus_oleh'))
            ->filter()
            ->unique()
            ->values();

        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        return $deletedRiwayatRows->map(function ($row) use ($deletedTamiuMap, $deletedUsahaMap, $users) {
            $source = $row->jenis_punia === 'tamiu'
                ? $deletedTamiuMap->get($row->id_pembayaran)
                : $deletedUsahaMap->get($row->id_pembayaran);

            $deletedBy = $source && !empty($source->dihapus_oleh)
                ? $users->get($source->dihapus_oleh)
                : null;

            $row->subjek_nama = $row->jenis_punia === 'tamiu'
                ? ($source?->pendatang?->nama ?: '-')
                : ($source?->usaha?->detail?->nama_usaha ?: $source?->nama_donatur ?: '-');
            $row->subjek_label = $row->jenis_punia === 'tamiu' ? 'Krama Tamiu' : 'Unit Usaha';
            $row->catatan_hapus = $source?->catatan_hapus ?: '-';
            $row->tanggal_hapus = $source?->tanggal_hapus ?: $row->updated_at;
            $row->deleted_by_name = $deletedBy?->name ?: '-';

            return $row;
        })->filter(function ($row) {
            return $row->catatan_hapus !== '-';
        })->values();
    }

    protected function redirectToIndex(Request $request)
    {
        $query = array_filter([
            'banjar' => $request->input('filter_banjar'),
            'jenis' => $request->input('filter_jenis'),
            'tab' => $request->input('tab', $request->input('jenis_punia_tab', 'tamiu')),
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        $url = url('administrator/setor_punia');
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return redirect($url);
    }
}
