<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Sumbangan;
use App\Models\Danapunia;
use App\Models\Usaha;
use App\Models\PaymentChannel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LandingController extends Controller
{
    private function getVillageData()
    {
        $settingsPath = storage_path('app/settings.json');
        if (file_exists($settingsPath)) {
            return json_decode(file_get_contents($settingsPath), true);
        }
        return ['name' => 'SPDA']; // Fallback
    }

    public function berita()
    {
        $berita = Berita::where('aktif', '1')->orderBy('id_berita', 'desc')->paginate(10);
        $kategori_berita = \App\Models\Kategori_Berita::where('aktif', '1')->get();
        $village = $this->getVillageData();
        return view('front.pages.berita.index', compact('berita', 'village', 'kategori_berita'));
    }

    public function berita_kategori($id)
    {
        $berita = Berita::where('aktif', '1')->where('id_kategori_berita', $id)->orderBy('id_berita', 'desc')->paginate(10);
        $kategori_berita = \App\Models\Kategori_Berita::where('aktif', '1')->get();
        $village = $this->getVillageData();
        $current_category = \App\Models\Kategori_Berita::find($id);
        return view('front.pages.berita.index', compact('berita', 'village', 'kategori_berita', 'current_category'));
    }
    
    public function home()
    {
        $village = $this->getVillageData();
        $berita = Berita::where('aktif', '1')->orderBy('id_berita', 'desc')->take(5)->get();
        $programs = \App\Models\ProgramDonasi::where('aktif', '1')->orderBy('id_program_donasi', 'desc')->take(3)->get();

        return view('front.pages.home', compact('village', 'berita', 'programs'));
    }



    public function berita_detail($id)
    {
        $berita = Berita::with('komentar')->where('id_berita', $id)->firstOrFail();
        $recent_berita = Berita::where('aktif', '1')->where('id_berita', '!=', $id)->orderBy('id_berita', 'desc')->take(3)->get();
        $village = $this->getVillageData();
        return view('front.pages.berita.detail', compact('berita', 'recent_berita', 'village'));
    }

    public function berita_komentar(Request $request, $id)
        {
            $request->validate([
                'nama' => 'required|string|max:100',
                'komentar' => 'required|string|max:500'
            ]);

            \App\Models\KomentarBerita::create([
                'id_berita' => $id,
                'nama' => $request->nama,
                'komentar' => $request->komentar
            ]);

            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
        }


    public function punia()
    {
        $total_punia = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->sum('jumlah_dana');
        $village = $this->getVillageData();
        $kategori_punia = \App\Models\KategoriPunia::with(['alokasi' => function($q) {
            $q->where('aktif', '1')->orderBy('tanggal_alokasi', 'desc');
        }])->where('aktif', '1')->orderBy('nama_kategori', 'asc')->get();

        // Calculate total pengeluaran
        $total_pengeluaran = \App\Models\AlokasiPunia::where('aktif', '1')->sum('nominal');
        
        // Get recent pemasukan (income)
        $pemasukan = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->orderBy('tanggal_pembayaran', 'desc')
            ->take(10)
            ->get();
        
        // Get recent pengeluaran (expenses)
        $pengeluaran = \App\Models\AlokasiPunia::with('kategori')
            ->where('aktif', '1')
            ->orderBy('tanggal_alokasi', 'desc')
            ->take(10)
            ->get();
        
        // Prepare chart data by category
        $chart_data = $kategori_punia->map(function($kat) {
            return [
                'label' => $kat->nama_kategori,
                'value' => $kat->alokasi->sum('nominal'),
                'color' => $kat->warna ?? '#00a6eb'
            ];
        });

        return view('front.pages.punia', compact('total_punia', 'village', 'kategori_punia', 'total_pengeluaran', 'pemasukan', 'pengeluaran', 'chart_data'));
    }

    public function punia_pembayaran()
    {
        $village = $this->getVillageData();
        $kategori_punia = \App\Models\KategoriPunia::where('aktif', '1')->orderBy('nama_kategori', 'asc')->get();

        return view('front.pages.punia_pembayaran', compact('village', 'kategori_punia'));
    }

    public function punia_pembayaran_submit(Request $request)
    {
        $isAnonymous = $request->is_anonymous == '1';
        
        $rules = [
            'jumlah_dana' => 'required|numeric|min:10000',
            'is_anonymous' => 'nullable|in:0,1'
        ];
        
        if (!$isAnonymous) {
            $rules['nama'] = 'required|string|max:100';
            $rules['email'] = 'nullable|email|max:100';
            $rules['no_wa'] = 'nullable|string|max:20';
        }
        
        $request->validate($rules);

        $nama = $isAnonymous ? 'Anonim' : $request->nama;

        $danapunia = Danapunia::create([
            'nama_donatur' => $nama,
            'email' => $isAnonymous ? null : $request->email,
            'no_wa' => $isAnonymous ? null : $request->no_wa,
            'jumlah_dana' => $request->jumlah_dana,
            'is_anonymous' => $isAnonymous ? 1 : 0,
            'tanggal_pembayaran' => now(),
            'bulan' => date('m'),
            'tahun' => date('Y'),
            'aktif' => '1',
            'status_pembayaran' => 'pending'
        ]);

        return redirect()->route('public.payment_methods', [
            'amount' => $request->jumlah_dana,
            'order_id' => 'PN-' . $danapunia->id_dana_punia,
            'type' => 'punia'
        ]);
    }

    public function payment_methods(Request $request)
    {
        $amount = $request->amount;
        $order_id = $request->order_id;
        $type = $request->type;
        $village = $this->getVillageData();

        $xendit = new \App\Services\XenditService();
        $is_configured = $xendit->isConfigured();
        $channels = PaymentChannel::where('is_active', true)->get();

        return view('front.pages.payment_methods', compact('amount', 'order_id', 'type', 'village', 'is_configured', 'channels'));
    }

    public function punia_penggunaan_detail($id)
    {
        $kategori = \App\Models\KategoriPunia::with(['alokasi' => function($q) {
            $q->where('aktif', '1')->orderBy('tanggal_alokasi', 'desc');
        }])->where('id_kategori_punia', $id)->firstOrFail();
        
        $village = $this->getVillageData();
        
        return view('front.pages.punia_penggunaan_detail', compact('kategori', 'village'));
    }

    public function punia_alokasi_detail($id)
    {
        $alokasi = \App\Models\AlokasiPunia::with('kategori')
            ->where('id_alokasi_punia', $id)
            ->where('aktif', '1')
            ->firstOrFail();
        
        $village = $this->getVillageData();
        
        // Get recent alokasi from same category
        $recent_alokasi = \App\Models\AlokasiPunia::with('kategori')
            ->where('id_kategori_punia', $alokasi->id_kategori_punia)
            ->where('id_alokasi_punia', '!=', $id)
            ->where('aktif', '1')
            ->orderBy('tanggal_alokasi', 'desc')
            ->take(3)
            ->get();
        
        return view('front.pages.punia_alokasi_detail', compact('alokasi', 'village', 'recent_alokasi'));
    }

    public function punia_download_laporan(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        // Get village data
        $village = $this->getVillageData();
        
        // Get pemasukan for the month
        $pemasukan = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->whereMonth('tanggal_pembayaran', $month)
            ->whereYear('tanggal_pembayaran', $year)
            ->orderBy('tanggal_pembayaran', 'asc')
            ->get();
        
        // Get pengeluaran for the month
        $pengeluaran = \App\Models\AlokasiPunia::with('kategori')
            ->where('aktif', '1')
            ->whereMonth('tanggal_alokasi', $month)
            ->whereYear('tanggal_alokasi', $year)
            ->orderBy('tanggal_alokasi', 'asc')
            ->get();
        
        // Calculate totals
        $total_pemasukan = $pemasukan->sum('jumlah_dana');
        $total_pengeluaran = $pengeluaran->sum('nominal');
        $saldo = $total_pemasukan - $total_pengeluaran;
        
        // Month name
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $month_name = $months[(int)$month];
        
        $data = compact('village', 'pemasukan', 'pengeluaran', 'total_pemasukan', 'total_pengeluaran', 'saldo', 'month_name', 'year');
        
        $pdf = Pdf::loadView('pdf.laporan_punia', $data);
        return $pdf->download('Laporan_Punia_' . $month_name . '_' . $year . '.pdf');
    }


    public function donasi()
    {
        $sumbangan = Sumbangan::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->orderBy('id_sumbangan_sukarela', 'desc')
            ->take(10)->get();
            
        $total_sumbangan = Sumbangan::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->sum('nominal');
        $village = $this->getVillageData();

        // Get kategori donasi and program donasi
        $kategori_donasi = \App\Models\KategoriDonasi::where('aktif', '1')->orderBy('nama_kategori', 'asc')->get();
        $programs = \App\Models\ProgramDonasi::where('aktif', '1')->orderBy('id_program_donasi', 'desc')->get();

        return view('front.pages.donasi', compact('sumbangan', 'total_sumbangan', 'village', 'kategori_donasi', 'programs'));
    }

    public function donasi_detail($id)
    {
        $program = \App\Models\ProgramDonasi::with('kategori')->where('id_program_donasi', $id)->firstOrFail();
        $village = $this->getVillageData();
        $recent_programs = \App\Models\ProgramDonasi::where('aktif', '1')
            ->where('id_program_donasi', '!=', $id)
            ->orderBy('id_program_donasi', 'desc')
            ->take(3)
            ->get();

        // Get recent donors for this program
        $donatur = Sumbangan::where('id_program_donasi', $id)
            ->where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->orderBy('id_sumbangan_sukarela', 'desc')
            ->take(10)
            ->get();

        return view('front.pages.donasi_detail', compact('program', 'village', 'recent_programs', 'donatur'));
    }

    public function donasi_pembayaran($id)
    {
        $program = \App\Models\ProgramDonasi::with('kategori')->where('id_program_donasi', $id)->firstOrFail();
        $village = $this->getVillageData();
        
        return view('front.pages.donasi_pembayaran', compact('program', 'village'));
    }


    public function donasi_post(Request $request)
    {
        $isAnonymous = $request->cmb_kategori_sumbangan == '1';
        $request->validate([
            'text_minimal_pembayaran' => 'required|numeric|min:10000',
            'id_program_donasi' => 'required|exists:tb_program_donasi,id_program_donasi'
        ]);

        $id = Sumbangan::create([
            'id_program_donasi' => $request->id_program_donasi,
            'nama' => $isAnonymous ? 'Anonim' : $request->text_title_new,
            'status_donatur' => $request->cmb_kategori_sumbangan,
            'nominal' => $request->text_minimal_pembayaran,
            'deskripsi' => $request->text_pesan ?? '',
            'tanggal' => now(),
            'aktif' => '1',
            'status_pembayaran' => 'pending'
        ])->id_sumbangan_sukarela;

        return redirect()->route('public.payment_methods', [
            'amount' => $request->text_minimal_pembayaran,
            'order_id' => 'DN-' . $id,
            'type' => 'donasi'
        ]);
    }

    public function unit_usaha()
        {
            $selected_banjar = request()->get('banjar', 'all');

            // Build query with proper joins to get banjar name and total donations
            $query = Usaha::select('tb_usaha.id_usaha', 'tb_detail_usaha.nama_usaha', 'tb_detail_usaha.logo', 'tb_data_banjar.nama_banjar')
                        ->selectRaw('COALESCE(SUM(tb_dana_punia.jumlah_dana), 0) as total_donasi')
                        ->join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', '=', 'tb_usaha.id_detail_usaha')
                        ->leftJoin('tb_data_banjar', 'tb_data_banjar.id_data_banjar', '=', 'tb_detail_usaha.id_banjar')
                        ->leftJoin('tb_dana_punia', function($join) {
                            $join->on('tb_dana_punia.id_usaha', '=', 'tb_usaha.id_usaha')
                                 ->where('tb_dana_punia.aktif', '=', '1')
                                 ->where('tb_dana_punia.status_pembayaran', '=', 'completed');
                        })
                        ->where('tb_usaha.aktif_status', '1')
                        ->groupBy('tb_usaha.id_usaha', 'tb_detail_usaha.nama_usaha', 'tb_detail_usaha.logo', 'tb_data_banjar.nama_banjar');

            // Filter by banjar if selected
            if ($selected_banjar !== 'all') {
                $query->where('tb_detail_usaha.id_banjar', $selected_banjar);
            }

            $usaha = $query->orderBy('tb_usaha.id_usaha', 'desc')->paginate(15);

            $total_usaha = Usaha::where("aktif_status", "1")->count();
            // Calculate total contributions from dana punia table where id_usaha is not null
            $total_kontribusi = Danapunia::whereNotNull('id_usaha')
                ->where('aktif', '1')
                ->where('status_pembayaran', 'completed')
                ->sum('jumlah_dana');
            $banjar_list = \App\Models\Banjar::where('aktif', '1')->orderBy('nama_banjar', 'asc')->get();
            $village = $this->getVillageData();
            return view('front.pages.unit_usaha', compact('usaha', 'village', 'total_usaha', 'total_kontribusi', 'selected_banjar', 'banjar_list'));
        }
}
