<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Pendatang;
use App\Models\PuniaPendatang;
use App\Models\Usaha;
use App\Models\Danapunia;
use App\Models\Banjar;
use App\Models\AcaraPunia;
use App\Models\Detail_Usaha;
use App\Models\Kategori_Usaha;
use App\Models\Jadwal_Interview;
use App\Models\List_Skill_Tk;
use App\Services\BagiHasilService;
use Barryvdh\DomPDF\Facade\Pdf;

class PenagihController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private function getPenagihBanjar()
    {
        $user = Auth::user();
        return Banjar::where('id_data_banjar', $user->id_banjar)->first();
    }

    private function buildPuniaRutinLabel(string $bulanTahun): string
    {
        return 'Punia rutin bulan ' . \Carbon\Carbon::createFromFormat('Y-m', $bulanTahun)->translatedFormat('F Y');
    }

    private function resolvePendatangPuniaBulanan(Pendatang $pendatang, int $bulan, int $tahun): PuniaPendatang
    {
        $bulanTahun = sprintf('%04d-%02d', $tahun, $bulan);
        $legacyFormat = sprintf('%02d/%04d', $bulan, $tahun);
        $nominal = (float) $pendatang->effective_punia_nominal;

        $punia = PuniaPendatang::where('id_pendatang', $pendatang->id_pendatang)
            ->where('jenis_punia', 'rutin')
            ->where('aktif', '1')
            ->where(function ($query) use ($bulanTahun, $legacyFormat) {
                $query->where('bulan_tahun', $bulanTahun)
                    ->orWhere('bulan_tahun', $legacyFormat);
            })
            ->first();

        if (!$punia) {
            return PuniaPendatang::create([
                'id_pendatang' => $pendatang->id_pendatang,
                'jenis_punia' => 'rutin',
                'periode_rutin' => 'bulanan',
                'bulan_tahun' => $bulanTahun,
                'nominal' => $nominal,
                'status_pembayaran' => 'belum_bayar',
                'keterangan' => $this->buildPuniaRutinLabel($bulanTahun),
                'petugas_id' => Auth::id(),
                'aktif' => '1',
            ]);
        }

        if ($punia->status_pembayaran !== 'lunas' && $nominal > 0 && (float) $punia->nominal <= 0) {
            $punia->update([
                'nominal' => $nominal,
                'keterangan' => $punia->keterangan ?: $this->buildPuniaRutinLabel($bulanTahun),
            ]);
            $punia->refresh();
        }

        return $punia;
    }

    public function index()
    {
        $banjar = $this->getPenagihBanjar();

        $pendatangCount = 0;
        $usahaCount = 0;
        $tagihanBelumBayar = 0;
        $totalTerkumpul = 0;

        if ($banjar) {
            $pendatangCount = Pendatang::where('id_data_banjar', $banjar->id_data_banjar)
                ->where('status', 'aktif')->where('aktif', '1')->count();

            $usahaCount = Usaha::join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', 'tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $banjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')->count();

            $pendatangIds = Pendatang::where('id_data_banjar', $banjar->id_data_banjar)
                ->where('aktif', '1')->pluck('id_pendatang');

            $tagihanBelumBayar = PuniaPendatang::whereIn('id_pendatang', $pendatangIds)
                ->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();

            $bulanIni = date('Y-m');
            $totalTerkumpul = PuniaPendatang::whereIn('id_pendatang', $pendatangIds)
                ->where('status_pembayaran', 'lunas')->where('aktif', '1')
                ->whereRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') = ?", [$bulanIni])
                ->sum('nominal');

            $usahaIds = Usaha::join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', 'tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $banjar->id_data_banjar)
                ->pluck('tb_usaha.id_usaha');

            $totalTerkumpul += Danapunia::whereIn('id_usaha', $usahaIds)
                ->where('aktif', '1')->where('status_pembayaran', 'completed')
                ->where('bulan_punia', (int) date('m'))
                ->where('tahun_punia', (int) date('Y'))
                ->sum('jumlah_dana');
        }

        return view('backend.penagih.home', compact('banjar', 'pendatangCount', 'usahaCount', 'tagihanBelumBayar', 'totalTerkumpul'));
    }

    public function pendatang()
    {
        $banjar = $this->getPenagihBanjar();
        $pendatangList = collect([]);
        $acaraList = AcaraPunia::where('aktif', '1')->orderBy('created_at', 'desc')->get();

        if ($banjar) {
            $pendatangList = Pendatang::with(['banjar', 'puniaPendatang' => function ($q) {
                $q->where('aktif', '1');
            }])
                ->where('id_data_banjar', $banjar->id_data_banjar)
                ->where('aktif', '1')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $recentPayments = collect([]);
        if ($banjar) {
            $pendatangIds = Pendatang::where('id_data_banjar', $banjar->id_data_banjar)
                ->where('aktif', '1')
                ->pluck('id_pendatang');

            $recentPayments = PuniaPendatang::with('pendatang')
                ->whereIn('id_pendatang', $pendatangIds)
                ->where('status_pembayaran', 'lunas')
                ->where('aktif', '1')
                ->orderBy('tanggal_bayar', 'desc')
                ->limit(3)
                ->get();
        }

        return view('backend.penagih.pendatang', compact('banjar', 'pendatangList', 'acaraList', 'recentPayments'));
    }

    public function pendatangDetail($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::with(['banjar', 'puniaPendatang' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        return view('backend.penagih.pendatang_detail', compact('pendatang', 'banjar'));
    }

    public function kartuPunia($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $selectedYear = (int) request('year', date('Y'));
        $payments = PuniaPendatang::where('id_pendatang', $id)
            ->where('jenis_punia', 'rutin')
            ->where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where(function($q) use ($selectedYear) {
                $q->where('bulan_tahun', 'LIKE', $selectedYear . '-%')
                  ->orWhereRaw("YEAR(STR_TO_DATE(bulan_tahun, '%m/%Y')) = ?", [$selectedYear]);
            })
            ->get()
            ->keyBy(function($item) {
                if (strpos($item->bulan_tahun, '/') !== false) {
                    return (int) substr($item->bulan_tahun, 0, 2);
                }
                return (int) substr($item->bulan_tahun, 5, 2);
            });

        $totalKontribusi = $payments->sum('nominal');
        $currentDateFormatted = now()->translatedFormat('d M');

        return view('backend.penagih.pendatang_kartu_punia', compact('pendatang', 'payments', 'selectedYear', 'totalKontribusi', 'currentDateFormatted', 'banjar'));
    }

    public function bayarKartuPunia(Request $request)
    {
        $request->validate([
            'id_pendatang' => 'required|exists:tb_pendatang,id_pendatang',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'metode_pembayaran' => 'required|in:cash',
        ]);

        $pendatang = Pendatang::findOrFail($request->id_pendatang);

        // Verify banjar access
        $banjar = $this->getPenagihBanjar();
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $punia = $this->resolvePendatangPuniaBulanan($pendatang, (int) $request->bulan, (int) $request->tahun);

        if ((float) $punia->nominal <= 0) {
            return redirect()->back()->with('error', 'Nominal iuran belum diatur. Periksa setting global atau nominal khusus pendatang ini.');
        }

        $punia->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'petugas_id' => Auth::id(),
        ]);

        // Split bagi hasil
        BagiHasilService::splitPayment(
            'tamiu',
            $punia->id_punia_pendatang,
            $pendatang->id_data_banjar,
            $punia->nominal,
            $request->metode_pembayaran,
            now()->toDateString()
        );

        return redirect()->back()->with('success', 'Pembayaran berhasil dicatat');
    }

    public function initiateKartuPuniaOnline(Request $request)
    {
        $request->validate([
            'id_pendatang' => 'required|exists:tb_pendatang,id_pendatang',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
        ]);

        $pendatang = Pendatang::findOrFail($request->id_pendatang);

        $banjar = $this->getPenagihBanjar();
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $punia = $this->resolvePendatangPuniaBulanan($pendatang, (int) $request->bulan, (int) $request->tahun);

        if ($punia->status_pembayaran === 'lunas') {
            return redirect()->back()->with('error', 'Tagihan untuk periode ini sudah lunas.');
        }

        if ((float) $punia->nominal <= 0) {
            return redirect()->back()->with('error', 'Nominal iuran belum diatur. Periksa setting global atau nominal khusus pendatang ini.');
        }

        return redirect()->route('public.payment_methods', [
            'amount' => $punia->nominal,
            'order_id' => 'TM-' . $punia->id_punia_pendatang,
            'type' => 'punia_pendatang',
            'context' => 'penagih',
        ]);
    }

    public function bayarPuniaPendatang(Request $request, $id)
    {
        $punia = PuniaPendatang::findOrFail($id);

        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($punia->id_pendatang);
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $punia->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $request->input('metode_pembayaran', 'cash'),
            'tanggal_bayar' => now(),
            'petugas_id' => Auth::id(),
        ]);

        // Split bagi hasil
        BagiHasilService::splitPayment(
            'tamiu',
            $punia->id_punia_pendatang,
            $pendatang->id_data_banjar,
            $punia->nominal,
            $request->input('metode_pembayaran', 'cash'),
            now()->toDateString()
        );

        return redirect()->back()->with('success', 'Pembayaran punia berhasil dicatat');
    }

    public function generateTagihan($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $nominal = (float) $pendatang->effective_punia_nominal;

        if ($nominal > 0) {
            $bulanTahun = date('Y-m');
            $bulanTahunOld = date('m/Y');
            $existing = PuniaPendatang::where('id_pendatang', $id)
                ->where('jenis_punia', 'rutin')
                ->where('aktif', '1')
                ->where(function($q) use ($bulanTahun, $bulanTahunOld) {
                    $q->where('bulan_tahun', $bulanTahun)
                      ->orWhere('bulan_tahun', $bulanTahunOld);
                })
                ->first();

            if (!$existing) {
                PuniaPendatang::create([
                    'id_pendatang' => $id,
                    'jenis_punia' => 'rutin',
                    'periode_rutin' => 'bulanan',
                    'bulan_tahun' => $bulanTahun,
                    'nominal' => $nominal,
                    'status_pembayaran' => 'belum_bayar',
                    'keterangan' => 'Punia rutin bulan ' . now()->translatedFormat('F Y'),
                    'petugas_id' => Auth::id(),
                    'aktif' => '1',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Tagihan bulan ini berhasil di-generate');
    }

    public function usaha()
    {
        $banjar = $this->getPenagihBanjar();
        $usahaList = collect([]);

        $selectedMonth = (int) request('bulan', date('m'));
        $selectedYear = (int) request('tahun', date('Y'));

        if ($banjar) {
            $usahaList = Usaha::join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', 'tb_usaha.id_detail_usaha')
                ->leftJoin('tb_kategori_usaha', 'tb_kategori_usaha.id_kategori_usaha', 'tb_usaha.id_jenis_usaha')
                ->where('tb_detail_usaha.id_banjar', $banjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->select('tb_usaha.*', 'tb_detail_usaha.*', 'tb_kategori_usaha.nama_kategori_usaha')
                ->orderBy('tb_detail_usaha.nama_usaha', 'asc')
                ->get();
        }

        $usahaWithPayment = [];
        foreach ($usahaList as $u) {
            $payment = Danapunia::where('id_usaha', $u->id_usaha)
                ->where('aktif', '1')
                ->where('status_pembayaran', 'completed')
                ->where('bulan_punia', $selectedMonth)
                ->where('tahun_punia', $selectedYear)
                ->first();

            $usahaWithPayment[] = [
                'usaha' => $u,
                'payment' => $payment,
            ];
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('backend.penagih.usaha', compact('banjar', 'usahaWithPayment', 'usahaList', 'selectedMonth', 'selectedYear', 'months'));
    }

    public function usahaDetail($id)
    {
        $banjar = $this->getPenagihBanjar();
        $rows = Usaha::get_detailUsaha($id);

        // Security: only allow viewing usaha from penagih's banjar
        if ($banjar && isset($rows->id_banjar) && $rows->id_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $selectedYear = (int) request('tahun', date('Y'));
        $payments = [];
        for ($m = 1; $m <= 12; $m++) {
            $payment = Danapunia::where('id_usaha', $id)
                ->where('aktif', '1')
                ->where('status_pembayaran', 'completed')
                ->where('bulan_punia', $m)
                ->where('tahun_punia', $selectedYear)
                ->first();
            $payments[$m] = $payment;
        }

        return view('backend.penagih.usaha_detail', compact('rows', 'payments', 'selectedYear', 'banjar'));
    }

    public function usahaBayarManual(Request $request)
    {
        $request->validate([
            'id_usaha' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'jumlah_dana' => 'required|numeric|min:1000',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string|in:tunai,transfer,qris',
        ]);

        $buktiFile = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $buktiFile = 'penagih_' . time() . '_' . $request->id_usaha . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('bukti_pembayaran'), $buktiFile);
        }

        $danapunia = Danapunia::create([
            'id_usaha' => $request->id_usaha,
            'jumlah_dana' => $request->jumlah_dana,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'bulan_punia' => $request->bulan,
            'tahun_punia' => $request->tahun,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'metode_pembayaran' => $request->metode_pembayaran,
            'metode' => $request->metode_pembayaran,
            'bukti_pembayaran' => $buktiFile,
            'status_pembayaran' => 'completed',
            'status_verifikasi' => 'approved',
            'aktif' => '1',
        ]);

        // Split bagi hasil
        $usaha = Usaha::with('detail')->find($request->id_usaha);
        $idBanjar = $usaha && $usaha->detail ? $usaha->detail->id_banjar : null;
        if ($idBanjar) {
            BagiHasilService::splitPayment(
                'usaha',
                $danapunia->id_dana_punia,
                $idBanjar,
                $request->jumlah_dana,
                $request->metode_pembayaran === 'tunai' ? 'cash' : $request->metode_pembayaran,
                $request->tanggal_pembayaran
            );
        }

        return redirect('administrator/penagih/usaha/detail/' . $request->id_usaha)
            ->with('success', 'Pembayaran bulan ' . $request->bulan . '/' . $request->tahun . ' berhasil disimpan.');
    }

    public function usahaInitiateOnline(Request $request)
    {
        $request->validate([
            'id_usaha' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'jumlah_dana' => 'required|numeric|min:1000',
        ]);

        $banjar = $this->getPenagihBanjar();
        $rows = Usaha::get_detailUsaha($request->id_usaha);

        if ($banjar && isset($rows->id_banjar) && $rows->id_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $existing = Danapunia::where('id_usaha', $request->id_usaha)
            ->where('aktif', '1')
            ->where('bulan_punia', $request->bulan)
            ->where('tahun_punia', $request->tahun)
            ->orderByDesc('id_dana_punia')
            ->first();

        if ($existing && $existing->status_pembayaran === 'completed') {
            return redirect()->back()->with('error', 'Iuran unit usaha untuk periode ini sudah lunas.');
        }

        if (!$existing) {
            $existing = Danapunia::create([
                'id_usaha' => $request->id_usaha,
                'jumlah_dana' => $request->jumlah_dana,
                'tanggal_pembayaran' => now(),
                'bulan_punia' => $request->bulan,
                'tahun_punia' => $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status_pembayaran' => 'pending',
                'aktif' => '1',
            ]);
        } elseif ($existing->status_pembayaran !== 'completed') {
            $existing->update([
                'jumlah_dana' => $request->jumlah_dana,
                'bulan_punia' => $request->bulan,
                'tahun_punia' => $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
        }

        return redirect()->route('public.payment_methods', [
            'amount' => $existing->jumlah_dana,
            'order_id' => 'PN-' . $existing->id_dana_punia,
            'type' => 'punia',
            'context' => 'penagih',
        ]);
    }

    // =========================================================================
    // PENDATANG CRUD (create, edit, update, delete, toggle)
    // =========================================================================

    public function pendatangCreate()
    {
        $banjar = $this->getPenagihBanjar();
        return view('backend.penagih.pendatang_create', compact('banjar'));
    }

    public function pendatangStore(Request $request)
    {
        $banjar = $this->getPenagihBanjar();

        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_pendatang,nik',
            'asal' => 'required|string|max:200',
            'no_hp' => 'required|string|max:20',
            'alamat_tinggal' => 'nullable|string',
            'tinggal_dari' => 'nullable|date',
            'tinggal_sampai' => 'nullable|date|after_or_equal:tinggal_dari',
            'tinggal_belum_yakin' => 'nullable|boolean',
        ]);

        $data = $request->only(['nama', 'nik', 'asal', 'no_hp', 'alamat_tinggal', 'tinggal_dari', 'tinggal_sampai']);
        $data['tinggal_belum_yakin'] = $request->has('tinggal_belum_yakin');
        $data['id_data_banjar'] = $banjar ? $banjar->id_data_banjar : null;
        $data['use_global_punia'] = true;
        $data['status'] = 'aktif';
        $data['aktif'] = '1';

        $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
        $data['punia_rutin_bulanan'] = $settings['punia_pendatang_global'] ?? 0;

        $pendatang = Pendatang::create($data);

        if ($pendatang->effective_punia_nominal > 0) {
            PuniaPendatang::create([
                'id_pendatang' => $pendatang->id_pendatang,
                'jenis_punia' => 'rutin',
                'periode_rutin' => 'bulanan',
                'bulan_tahun' => now()->format('Y-m'),
                'nominal' => $pendatang->effective_punia_nominal,
                'keterangan' => 'Punia rutin bulan ' . now()->translatedFormat('F Y'),
                'petugas_id' => Auth::id(),
            ]);
        }

        return redirect('administrator/penagih/pendatang')->with('success', 'Data pendatang berhasil ditambahkan');
    }

    public function pendatangEdit($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        return view('backend.penagih.pendatang_edit', compact('pendatang', 'banjar'));
    }

    public function pendatangUpdate(Request $request, $id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_pendatang,nik,' . $id . ',id_pendatang',
            'asal' => 'required|string|max:200',
            'no_hp' => 'required|string|max:20',
            'alamat_tinggal' => 'nullable|string',
            'punia_rutin_bulanan' => 'required|numeric|min:0',
            'tinggal_dari' => 'nullable|date',
            'tinggal_sampai' => 'nullable|date|after_or_equal:tinggal_dari',
            'tinggal_belum_yakin' => 'nullable|boolean',
        ]);

        $useGlobal = $request->has('use_global_punia') && $request->use_global_punia == '1';
        $data = $request->only(['nama', 'nik', 'asal', 'no_hp', 'alamat_tinggal', 'punia_rutin_bulanan', 'tinggal_dari', 'tinggal_sampai']);
        $data['use_global_punia'] = $useGlobal;
        $data['tinggal_belum_yakin'] = $request->has('tinggal_belum_yakin');

        if ($useGlobal) {
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $data['punia_rutin_bulanan'] = $settings['punia_pendatang_global'] ?? 0;
        }

        $pendatang->update($data);

        return redirect('administrator/penagih/pendatang/detail/' . $id)->with('success', 'Data pendatang berhasil diupdate');
    }

    public function pendatangDelete($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $pendatang->update(['aktif' => '0']);
        return redirect('administrator/penagih/pendatang')->with('success', 'Data pendatang berhasil dihapus');
    }

    public function pendatangToggle($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $pendatang->status = $pendatang->status === 'aktif' ? 'nonaktif' : 'aktif';
        $pendatang->save();

        return redirect()->back()->with('success', 'Status pendatang berhasil diubah');
    }

    public function hapusKartuPunia(Request $request)
    {
        $request->validate([
            'id_punia_pendatang' => 'required|exists:tb_punia_pendatang,id_punia_pendatang',
            'catatan_hapus' => 'required|string|min:5|max:500',
        ]);

        $punia = PuniaPendatang::findOrFail($request->id_punia_pendatang);

        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($punia->id_pendatang);
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $punia->update([
            'aktif' => '0',
            'catatan_hapus' => $request->catatan_hapus,
            'dihapus_oleh' => Auth::id(),
            'tanggal_hapus' => now(),
        ]);

        return redirect()->back()->with('success', 'Record pembayaran berhasil dihapus.');
    }

    public function printKartuPunia($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);

        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $year = (int) request()->get('year', date('Y'));

        $payments = PuniaPendatang::where('id_pendatang', $id)
            ->where('jenis_punia', 'rutin')
            ->where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where(function($q) use ($year) {
                $q->where('bulan_tahun', 'LIKE', $year . '-%')
                  ->orWhereRaw("YEAR(STR_TO_DATE(bulan_tahun, '%m/%Y')) = ?", [$year]);
            })
            ->get()
            ->keyBy(function($item) {
                if (strpos($item->bulan_tahun, '/') !== false) {
                    return (int) substr($item->bulan_tahun, 0, 2);
                }
                return (int) substr($item->bulan_tahun, 5, 2);
            });

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $settingsPath = storage_path('app/settings.json');
        $village = file_exists($settingsPath)
            ? json_decode(file_get_contents($settingsPath), true)
            : ['name' => 'SPDA'];

        $data = compact('pendatang', 'year', 'months', 'payments', 'village', 'currentYear', 'currentMonth');

        $pdf = Pdf::loadView('pdf.kartu_punia_pendatang', $data);
        return $pdf->download('Kartu_Punia_' . $pendatang->nama . '_' . $year . '.pdf');
    }

    // =========================================================================
    // USAHA CRUD (store)
    // =========================================================================

    public function usahaStore(Request $request)
    {
        $banjar = $this->getPenagihBanjar();

        $request->validate([
            'text_title_new' => 'required|string|max:200',
            'cmb_kategori_usaha' => 'required|integer',
            'text_namapngg_new' => 'required|string|max:200',
            'text_username_new' => 'required|string|max:100',
            'text_password_new' => 'required|string|min:6',
        ]);

        $request->merge([
            'text_desc_new' => $banjar ? $banjar->id_data_banjar : '',
        ]);

        Usaha::post_data_usaha($request);

        return redirect('administrator/penagih/usaha')->with('success', 'Unit usaha berhasil ditambahkan');
    }
}
