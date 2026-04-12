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

class PenagihController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private function getPenagihBanjar()
    {
        $user = Auth::user();
        return Banjar::where('id_data_banjar', $user->id_banjar)->first();
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

        return view('backend.penagih.pendatang', compact('banjar', 'pendatangList', 'acaraList'));
    }

    public function pendatangDetail($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::with(['puniaPendatang' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Security: only allow viewing pendatang from penagih's banjar
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

        $year = request('year', date('Y'));
        $puniaList = PuniaPendatang::where('id_pendatang', $id)
            ->where('jenis_punia', 'rutin')
            ->where('aktif', '1')
            ->whereRaw("YEAR(STR_TO_DATE(bulan_tahun, '%m/%Y')) = ?", [$year])
            ->orderByRaw("STR_TO_DATE(bulan_tahun, '%m/%Y') ASC")
            ->get();

        return view('backend.penagih.pendatang_kartu_punia', compact('pendatang', 'puniaList', 'year', 'banjar'));
    }

    public function bayarKartuPunia(Request $request)
    {
        $request->validate([
            'id_punia' => 'required',
            'metode_pembayaran' => 'required|in:cash,qris',
        ]);

        $punia = PuniaPendatang::findOrFail($request->id_punia);

        // Verify banjar access
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($punia->id_pendatang);
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $punia->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dicatat');
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

        return redirect()->back()->with('success', 'Pembayaran punia berhasil dicatat');
    }

    public function generateTagihan($id)
    {
        $banjar = $this->getPenagihBanjar();
        $pendatang = Pendatang::findOrFail($id);
        if ($banjar && $pendatang->id_data_banjar != $banjar->id_data_banjar) {
            abort(403);
        }

        $nominal = $pendatang->punia_rutin_bulanan;
        if ($pendatang->use_global_punia) {
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $nominal = $settings['punia_pendatang_global'] ?? 0;
        }

        if ($nominal > 0) {
            $bulanTahun = date('m/Y');
            $existing = PuniaPendatang::where('id_pendatang', $id)
                ->where('bulan_tahun', $bulanTahun)
                ->where('jenis_punia', 'rutin')
                ->where('aktif', '1')
                ->first();

            if (!$existing) {
                PuniaPendatang::create([
                    'id_pendatang' => $id,
                    'jenis_punia' => 'rutin',
                    'periode_rutin' => 'bulanan',
                    'bulan_tahun' => $bulanTahun,
                    'nominal' => $nominal,
                    'status_pembayaran' => 'belum_bayar',
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

        Danapunia::create([
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

        return redirect('administrator/penagih/usaha/detail/' . $request->id_usaha)
            ->with('success', 'Pembayaran bulan ' . $request->bulan . '/' . $request->tahun . ' berhasil disimpan.');
    }
}
