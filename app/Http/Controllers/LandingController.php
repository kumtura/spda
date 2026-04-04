<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Sumbangan;
use App\Models\Danapunia;
use App\Models\Usaha;
use App\Models\PaymentChannel;
use App\Models\ObjekWisata;
use App\Models\TiketWisata;
use App\Models\KategoriTiket;
use App\Models\TiketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LandingController extends Controller
{
    private function getWisataAvailabilityForDate(int $objekWisataId, string $tanggalKunjungan): array
    {
        $objek = ObjekWisata::select('id_objek_wisata', 'batas_tiket_harian')->findOrFail($objekWisataId);

        if (!$objek->batas_tiket_harian) {
            return [
                'unlimited' => true,
                'limit' => null,
                'sold' => 0,
                'available' => null,
            ];
        }

        $sold = (int) TiketDetail::query()
            ->join('tb_tiket_wisata', 'tb_tiket_wisata.id_tiket', '=', 'tb_tiket_detail.id_tiket')
            ->where('tb_tiket_wisata.id_objek_wisata', $objekWisataId)
            ->whereIn('tb_tiket_wisata.status_pembayaran', ['paid', 'completed', 'pending'])
            ->whereDate('tb_tiket_wisata.tanggal_kunjungan', $tanggalKunjungan)
            ->sum('tb_tiket_detail.jumlah');

        return [
            'unlimited' => false,
            'limit' => (int) $objek->batas_tiket_harian,
            'sold' => $sold,
            'available' => max(0, (int) $objek->batas_tiket_harian - $sold),
        ];
    }

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
        $totalKramaTamiu = \App\Models\Pendatang::where('aktif', '1')->where('status', 'aktif')->count();

        return view('front.pages.home', compact('village', 'berita', 'programs', 'totalKramaTamiu'));
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

    public function payment_manual(Request $request)
    {
        $order_id = $request->order_id;
        $amount = $request->amount ?? 0;
        $type = $request->type;
        $village = $this->getVillageData();

        // If amount is 0, try to get from order
        if($amount == 0) {
            $order_parts = explode('-', $order_id);
            $id = $order_parts[1] ?? null;
            
            if($type === 'punia') {
                $punia = Danapunia::find($id);
                $amount = $punia->jumlah_dana ?? 0;
            } else {
                $donasi = Sumbangan::find($id);
                $amount = $donasi->nominal ?? 0;
            }
        }

        return view('front.pages.payment_manual', compact('order_id', 'amount', 'type', 'village'));
    }

    public function payment_manual_submit(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:punia,donasi',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Upload bukti transfer
        $file = $request->file('bukti_transfer');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'bukti_transfer';
        
        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }
        
        $file->move(public_path($path), $filename);
        $buktiPath = $path . '/' . $filename;

        // Get order details
        $order_parts = explode('-', $request->order_id);
        $id = $order_parts[1] ?? null;

        if($request->type === 'punia') {
            $punia = Danapunia::find($id);
            if($punia) {
                $punia->update([
                    'metode_pembayaran' => 'transfer_manual',
                    'bukti_transfer' => $buktiPath,
                    'status_verifikasi' => 'pending',
                    'catatan_verifikasi' => $request->catatan
                ]);
            }
        } else {
            $donasi = Sumbangan::find($id);
            if($donasi) {
                $donasi->update([
                    'metode_pembayaran' => 'transfer_manual',
                    'bukti_transfer' => $buktiPath,
                    'status_verifikasi' => 'pending',
                    'catatan_verifikasi' => $request->catatan
                ]);
            }
        }

        return redirect()->route('public.payment.manual.success', [
            'order_id' => $request->order_id,
            'type' => $request->type
        ]);
    }

    public function payment_manual_success(Request $request)
    {
        $order_id = $request->order_id;
        $type = $request->type;
        $village = $this->getVillageData();

        return view('front.pages.payment_manual_success', compact('order_id', 'type', 'village'));
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
        $tipeDonat = $request->tipe_donatur ?? 'masyarakat'; // 'usaha' or 'masyarakat'
        
        $request->validate([
            'text_minimal_pembayaran' => 'required|numeric|min:10000',
            'id_program_donasi' => 'required|exists:tb_program_donasi,id_program_donasi'
        ]);

        $data = [
            'id_program_donasi' => $request->id_program_donasi,
            'nama' => $isAnonymous ? 'Anonim' : $request->text_title_new,
            'status_donatur' => $tipeDonat == 'usaha' ? '3' : $request->cmb_kategori_sumbangan, // 3 = unit usaha
            'nominal' => $request->text_minimal_pembayaran,
            'deskripsi' => $request->text_pesan ?? '',
            'tanggal' => now(),
            'aktif' => '1',
            'status_pembayaran' => 'pending'
        ];

        // If unit usaha and not anonymous, save id_usaha and logo
        if($tipeDonat == 'usaha' && !$isAnonymous && Auth::check()) {
            $usaha = \App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->where('tb_usaha.username', Auth::user()->email)->first();
            if($usaha) {
                $data['id_usaha'] = $usaha->id_usaha;
                $data['profile'] = $usaha->logo; // Save logo for transparency
            }
        }

        $id = Sumbangan::create($data)->id_sumbangan_sukarela;

        // Redirect to payment method selection
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
                        ->selectRaw('(SELECT COUNT(*) FROM tb_jadwal_interview WHERE tb_jadwal_interview.id_usaha = tb_usaha.id_usaha AND tb_jadwal_interview.status_diterima = 1 AND tb_jadwal_interview.aktif = 1) as jumlah_tenaga_kerja')
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

    public function loker(Request $request)
    {
        $village = $this->getVillageData();
        $kategori_filter = $request->get('kategori', 'all');
        
        // Get all kategori usaha for filter
        $kategori_list = \App\Models\Kategori_Usaha::where('aktif', '1')->orderBy('nama_kategori_usaha', 'asc')->get();
        
        // Build query - get all lokers without status filter first to debug
        $query = \App\Models\Loker::with(['usaha.detail', 'usaha.kategori']);
        
        // Filter by kategori if selected
        if ($kategori_filter !== 'all') {
            $query->whereHas('usaha', function($q) use ($kategori_filter) {
                $q->where('id_jenis_usaha', $kategori_filter);
            });
        }
        
        $lokers = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('front.pages.loker', compact('lokers', 'village', 'kategori_list', 'kategori_filter'));
    }

    public function loker_detail($id)
    {
        $loker = \App\Models\Loker::with(['usaha.detail', 'usaha.kategori'])->findOrFail($id);
        $village = $this->getVillageData();
        
        // Get other lokers from same company
        $other_lokers = \App\Models\Loker::where('id_usaha', $loker->id_usaha)
            ->where('id_loker', '!=', $id)
            ->where('status', 'Buka')
            ->take(3)
            ->get();
        
        return view('front.pages.loker_detail', compact('loker', 'village', 'other_lokers'));
    }

    public function loker_apply_form($id)
    {
        $loker = \App\Models\Loker::with(['usaha.detail'])->findOrFail($id);
        
        if($loker->status != 'Buka') {
            return redirect()->route('public.loker.detail', $id)->with('error', 'Lowongan sudah ditutup');
        }
        
        $village = $this->getVillageData();
        return view('front.pages.loker_apply', compact('loker', 'village'));
    }

    public function loker_apply(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'required|integer|min:17|max:65',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        // Check if loker exists and is open
        $loker = \App\Models\Loker::findOrFail($id);
        if($loker->status != 'Buka') {
            return redirect()->back()->with('error', 'Lowongan sudah ditutup');
        }

        // Create or get karyawan record
        $karyawan = \App\Models\Karyawan::firstOrCreate(
            ['no_telp' => $request->no_telp],
            [
                'nama' => $request->nama,
                'email_karyawan' => $request->email,
                'jenis_kelamin' => $request->jenis_kelamin,
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'status' => '0',
                'aktif' => '1'
            ]
        );

        // Check if already applied
        $existing = \App\Models\Jadwal_Interview::where('id_karyawan', $karyawan->id_tenaga_kerja)
            ->where('id_loker', $id)
            ->where('aktif', '1')
            ->first();

        if($existing) {
            return redirect()->back()->with('error', 'Anda sudah melamar posisi ini');
        }

        // Handle file uploads
        $uploadedFiles = [];
        if($request->hasFile('files')) {
            $files = $request->file('files');
            
            // Limit to 5 files
            $files = array_slice($files, 0, 5);
            
            foreach($files as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'lamaran/dokumen';
                
                // Create directory if not exists
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                
                $file->move(public_path($path), $filename);
                $uploadedFiles[] = $path . '/' . $filename;
            }
        }

        // Create application
        \App\Models\Jadwal_Interview::create([
            'id_karyawan' => $karyawan->id_tenaga_kerja,
            'id_usaha' => $loker->id_usaha,
            'id_loker' => $id,
            'status_interview' => '0',
            'status_diterima' => '0',
            'dokumen_lamaran' => !empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
            'aktif' => '1'
        ]);

        $village = $this->getVillageData();
        $loker->load('usaha.detail'); // Ensure relations are loaded
        return view('front.pages.loker_apply_success', [
            'loker' => $loker,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'village' => $village
        ]);
    }
    
    // Unit Usaha specific pages
    public function usaha_donasi()
    {
        $programs = \App\Models\ProgramDonasi::where('aktif', '1')->orderBy('id_program_donasi', 'desc')->get();
        $kategori_donasi = \App\Models\KategoriDonasi::where('aktif', '1')->get();
        $village = $this->getVillageData();
        
        return view('backend.usaha.donasi', compact('programs', 'kategori_donasi', 'village'));
    }
    
    public function usaha_donasi_detail($id)
    {
        $program = \App\Models\ProgramDonasi::with('kategori')->findOrFail($id);
        $donatur = \App\Models\Sumbangan::where('id_program_donasi', $id)
            ->where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->orderBy('id_sumbangan_sukarela', 'desc')
            ->take(10)
            ->get();
        $village = $this->getVillageData();
        
        return view('backend.usaha.donasi_detail', compact('program', 'donatur', 'village'));
    }
    
    public function usaha_berita()
    {
        $berita = \App\Models\Berita::where('aktif', '1')->orderBy('id_berita', 'desc')->paginate(10);
        $village = $this->getVillageData();
        
        return view('backend.usaha.berita', compact('berita', 'village'));
    }
    
    public function usaha_berita_detail($id)
    {
        $berita = \App\Models\Berita::with('kategori')->findOrFail($id);
        $village = $this->getVillageData();
        
        return view('backend.usaha.berita_detail', compact('berita', 'village'));
    }

    public function usaha_punia_bayar(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',
            'id_usaha' => 'required|exists:tb_usaha,id_usaha'
        ]);

        $usaha = \App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.id_usaha', $request->id_usaha)->first();

        if(!$usaha) {
            return redirect()->back()->with('error', 'Data usaha tidak ditemukan');
        }

        $tahunDibayar = (int)$request->tahun;
        $bulanDibayar = (int)$request->bulan;
        
        // Check if already paid for this specific month and year
        $existing = \App\Models\Danapunia::where('id_usaha', $request->id_usaha)
            ->where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where('bulan_punia', $bulanDibayar)
            ->where('tahun_punia', $tahunDibayar)
            ->first();

        if($existing) {
            return redirect()->back()->with('error', 'Punia bulan ini sudah dibayar');
        }

        // Create pending punia record
        // tanggal_pembayaran = when payment is made (today)
        // bulan_punia & tahun_punia = which month is being paid
        $punia = \App\Models\Danapunia::create([
            'id_usaha' => $request->id_usaha,
            'nama_donatur' => $usaha->nama_usaha, // Add business name
            'jumlah_dana' => $usaha->minimal_bayar ?? 0,
            'tanggal_pembayaran' => now(), // When payment is made
            'bulan_punia' => $bulanDibayar, // Which month is being paid
            'tahun_punia' => $tahunDibayar, // Which year is being paid
            'aktif' => '1',
            'status_pembayaran' => 'pending'
        ]);

        // Redirect to payment method selection
        return redirect()->route('public.payment_methods', [
            'amount' => $usaha->minimal_bayar ?? 0,
            'order_id' => 'PN-' . $punia->id_dana_punia,
            'type' => 'punia'
        ]);
    }

    public function usaha_punia_print(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $usaha = \App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->join('tb_data_banjar', 'tb_data_banjar.id_data_banjar', '=', 'tb_detail_usaha.id_banjar')
            ->where('tb_usaha.username', Auth::user()->email)
            ->select('tb_usaha.*', 'tb_detail_usaha.*', 'tb_data_banjar.nama_banjar')
            ->first();

        if(!$usaha) {
            return redirect()->back()->with('error', 'Data usaha tidak ditemukan');
        }

        $currentYear = date('Y');
        $currentMonth = date('m');
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Get all payments for selected year
        $payments = [];
        $paymentsData = \App\Models\Danapunia::where('id_usaha', $usaha->id_usaha)
            ->where('aktif','1')
            ->where('status_pembayaran', 'completed')
            ->where('tahun_punia', $year)
            ->get();
        
        foreach($paymentsData as $p) {
            $month = (int)$p->bulan_punia;
            $payments[$month] = $p;
        }
        
        $village = $this->getVillageData();
        
        $data = compact('usaha', 'year', 'months', 'payments', 'village', 'currentYear', 'currentMonth');
        
        $pdf = Pdf::loadView('pdf.kartu_punia', $data);
        return $pdf->download('Kartu_Punia_' . $usaha->nama_usaha . '_' . $year . '.pdf');
    }

    public function usaha_punia_receipt(Request $request)
    {
        $id = (int) $request->get('id');
        if (!$id) {
            return redirect()->back()->with('error', 'ID pembayaran tidak valid');
        }

        $payment = \App\Models\Danapunia::where('id_dana_punia', $id)
            ->where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Verify ownership
        $usaha = \App\Models\Usaha::join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', 'tb_usaha.id_detail_usaha')
            ->join('tb_data_banjar', 'tb_data_banjar.id_data_banjar', '=', 'tb_detail_usaha.id_banjar')
            ->where('tb_usaha.id_usaha', $payment->id_usaha)
            ->where('tb_usaha.username', Auth::user()->email)
            ->select('tb_usaha.*', 'tb_detail_usaha.*', 'tb_data_banjar.nama_banjar')
            ->first();

        if (!$usaha) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $village = $this->getVillageData();
        $bulanName = $months[(int)$payment->bulan_punia] ?? '-';

        $data = compact('payment', 'usaha', 'village', 'bulanName');

        $pdf = Pdf::loadView('pdf.receipt_punia', $data);
        return $pdf->download('Receipt_Punia_' . $usaha->nama_usaha . '_' . $bulanName . '_' . $payment->tahun_punia . '.pdf');
    }

    public function usaha_loker_create(Request $request)
    {
        $request->validate([
            'id_usaha' => 'required|exists:tb_usaha,id_usaha',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string'
        ]);

        \App\Models\Loker::create([
            'id_usaha' => $request->id_usaha,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'Buka'
        ]);

        return redirect()->back()->with('success', 'Lowongan berhasil dibuat');
    }

    public function usaha_loker_detail($id)
    {
        $loker = \App\Models\Loker::with('usaha.detail')->findOrFail($id);
        
        // Verify ownership
        $myUsaha = \App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        
        if(!$myUsaha || $loker->id_usaha != $myUsaha->id_usaha) {
            return redirect()->route('administrator.usaha.home')->with('error', 'Akses ditolak');
        }
        
        $applicants = \App\Models\Jadwal_Interview::where('id_loker', $id)
            ->where('aktif', '1')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $village = $this->getVillageData();
        
        return view('backend.usaha.loker_detail', compact('loker', 'applicants', 'village'));
    }

    public function usaha_loker_accept(Request $request)
    {
        $request->validate([
            'id_jadwal_interview' => 'required|exists:tb_jadwal_interview,id_jadwal_interview'
        ]);

        $interview = \App\Models\Jadwal_Interview::find($request->id_jadwal_interview);
        
        if($interview) {
            $interview->update(['status_diterima' => '1']);
            return redirect()->back()->with('success', 'Pelamar berhasil diterima');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }

    public function usaha_loker_interview(Request $request)
    {
        $request->validate([
            'id_jadwal_interview' => 'required|exists:tb_jadwal_interview,id_jadwal_interview'
        ]);

        $interview = \App\Models\Jadwal_Interview::find($request->id_jadwal_interview);
        
        if($interview) {
            $interview->update(['status_interview' => '1']);
            return redirect()->back()->with('success', 'Pelamar dipindahkan ke tahap interview');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }

    public function usaha_loker_reject(Request $request)
    {
        $request->validate([
            'id_jadwal_interview' => 'required|exists:tb_jadwal_interview,id_jadwal_interview',
            'alasan' => 'nullable|string|max:500'
        ]);

        $interview = \App\Models\Jadwal_Interview::find($request->id_jadwal_interview);
        
        if($interview) {
            $interview->update([
                'aktif' => '0',
                'alasan_penolakan' => $request->alasan
            ]);
            return redirect()->back()->with('success', 'Pelamar berhasil ditolak');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }

    public function usaha_update_tk_counts(Request $request)
    {
        $request->validate([
            'jumlah_tk_total' => 'nullable|integer|min:0',
            'jumlah_tk_bali' => 'nullable|integer|min:0',
            'jumlah_tk_lokal' => 'nullable|integer|min:0',
        ]);

        $usaha = \App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', \Auth::user()->email)->first();

        if(!$usaha) {
            return redirect()->back()->with('error', 'Data usaha tidak ditemukan');
        }

        \App\Models\Detail_Usaha::where('id_detail_usaha', $usaha->id_detail_usaha)->update([
            'jumlah_tk_total' => $request->jumlah_tk_total,
            'jumlah_tk_bali' => $request->jumlah_tk_bali,
            'jumlah_tk_lokal' => $request->jumlah_tk_lokal,
        ]);

        return redirect()->back()->with('success', 'Data tenaga kerja berhasil diperbarui');
    }

    // Tiket Wisata Public Pages
    public function wisata()
    {
        return view('front.pages.wisata');
    }

    public function wisata_detail($id)
    {
        $objek = ObjekWisata::with('kategoriTiket')
            ->where('id_objek_wisata', $id)
            ->where('aktif', '1')
            ->where('status', 'aktif')
            ->firstOrFail();
        return view('front.pages.wisata_detail', compact('objek'));
    }

    public function wisata_beli($id)
    {
        $objek = ObjekWisata::with('kategoriTiket')
            ->where('id_objek_wisata', $id)
            ->where('aktif', '1')
            ->where('status', 'aktif')
            ->firstOrFail();
        return view('front.pages.wisata_beli', compact('objek'));
    }

    public function wisata_beli_submit(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required',
            'kategori' => 'required|array|min:1',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today'
        ]);

        // Calculate total
        $total = 0;
        $totalTicketQty = 0;
        $kategoriData = [];
        
        foreach ($request->kategori as $kategoriId => $qty) {
            if ($qty > 0) {
                $kategori = KategoriTiket::findOrFail($kategoriId);
                $subtotal = $kategori->harga * $qty;
                $total += $subtotal;
                $totalTicketQty += (int) $qty;
                
                $kategoriData[] = [
                    'id_kategori_tiket' => $kategoriId,
                    'nama_kategori' => $kategori->nama_kategori,
                    'jumlah' => $qty,
                    'harga_satuan' => $kategori->harga,
                    'subtotal' => $subtotal
                ];
            }
        }

        if ($total == 0) {
            return back()->with('error', 'Pilih minimal 1 tiket');
        }

        $availability = $this->getWisataAvailabilityForDate(
            (int) $request->id_objek_wisata,
            $request->tanggal_kunjungan
        );

        if (!$availability['unlimited'] && $availability['available'] < $totalTicketQty) {
            return back()
                ->withInput()
                ->with('error', 'Kuota tiket pada tanggal tersebut tidak mencukupi. Sisa tiket: ' . $availability['available']);
        }

        // Store in session for payment
        session([
            'tiket_data' => [
                'id_objek_wisata' => $request->id_objek_wisata,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'total_harga' => $total,
                'kategori' => $kategoriData,
            ]
        ]);

        return redirect()->to('wisata/payment/methods');
    }

    public function wisata_payment_proceed(Request $request)
    {
        if (!session('tiket_data')) {
            return redirect()->to('wisata');
        }

        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $tiketData = session('tiket_data');

        if (!$request->input('skip_biodata')) {
            $tiketData['nama_pengunjung'] = $request->input('nama_pengunjung');
            $tiketData['no_wa'] = $request->input('no_wa');
            $tiketData['email'] = $request->input('email');
        } else {
            $tiketData['nama_pengunjung'] = null;
            $tiketData['no_wa'] = null;
            $tiketData['email'] = null;
        }

        session(['tiket_data' => $tiketData]);

        $amount = $tiketData['total_harga'];
        $method = $request->input('payment_method');

        if ($method === 'manual') {
            return redirect()->to('wisata/payment/manual?amount=' . $amount);
        }

        if (str_starts_with($method, 'xendit:')) {
            $channel = substr($method, 7);
            return redirect()->to('wisata/payment/xendit?channel=' . urlencode($channel) . '&amount=' . $amount);
        }

        return redirect()->to('wisata/payment/methods');
    }

    public function wisata_payment_methods()
    {
        if (!session('tiket_data')) {
            return redirect()->to('wisata');
        }

        $tiketData = session('tiket_data');
        $objek = ObjekWisata::findOrFail($tiketData['id_objek_wisata']);
        $village = $this->getVillageData();
        
        $xendit = new \App\Services\XenditService();
        $is_configured = $xendit->isConfigured();
        $channels = PaymentChannel::where('is_active', true)->get();
        
        return view('front.pages.wisata_payment_methods', compact('tiketData', 'objek', 'village', 'is_configured', 'channels'));
    }

    public function wisata_payment_xendit(Request $request)
    {
        $tiketData = session('tiket_data');
        
        if (!$tiketData) {
            return redirect()->to('wisata')->with('error', 'Data tiket tidak ditemukan. Silakan ulangi pemesanan.');
        }

        $channel = $request->query('channel');
        $amount = $request->query('amount');

        // Create ticket record with pending status
        $kodeTimestamp = time();
        $kodeRandom = strtoupper(\Illuminate\Support\Str::random(6));
        $kodeTicket = "TKT-{$kodeTimestamp}-{$kodeRandom}";

        $tiket = TiketWisata::create([
            'kode_tiket' => $kodeTicket,
            'id_objek_wisata' => $tiketData['id_objek_wisata'],
            'nama_pengunjung' => $tiketData['nama_pengunjung'] ?? null,
            'email' => $tiketData['email'] ?? null,
            'no_wa' => $tiketData['no_wa'] ?? null,
            'total_harga' => $tiketData['total_harga'],
            'tanggal_kunjungan' => $tiketData['tanggal_kunjungan'],
            'metode_pembelian' => 'online',
            'metode_pembayaran' => $channel,
            'status_pembayaran' => 'pending',
            'status_tiket' => 'belum_digunakan',
            'qr_code' => $kodeTicket,
            'aktif' => '1'
        ]);

        // Create tiket details
        foreach ($tiketData['kategori'] as $detail) {
            TiketDetail::create([
                'id_tiket' => $tiket->id_tiket,
                'id_kategori_tiket' => $detail['id_kategori_tiket'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                'subtotal' => $detail['subtotal']
            ]);
        }

        // Initialize Xendit service
        $xendit = new \App\Services\XenditService();
        
        if (!$xendit->isConfigured()) {
            // Clear session before redirect
            session()->forget('tiket_data');
            return redirect()->back()->with('error', 'Layanan pembayaran sedang tidak tersedia.');
        }

        $external_id = $kodeTicket . '-' . time();
        $redirect_url = url('wisata/payment/result?order_id=' . $kodeTicket);
        $response = null;

        // Process payment based on channel type
        if (str_ends_with($channel, '_VA')) {
            $bank_code = str_replace('_VA', '', $channel);
            $response = $xendit->createVA($external_id, $bank_code, 'Pengunjung', $amount);
            
        } elseif (in_array($channel, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY'])) {
            $response = $xendit->createEWalletCharge($external_id, $amount, $channel, $kodeTicket, $redirect_url);
            
        } elseif (in_array($channel, ['QRIS', 'ID_QRIS'])) {
            $response = $xendit->createQRCode($external_id, $amount, $redirect_url);
            
        } else {
            // Clear session before redirect
            session()->forget('tiket_data');
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // Log the response
        \Illuminate\Support\Facades\Log::info("=== PAYLOAD XENDIT WISATA {$channel} ===", $response ?? []);

        // Check for errors
        if (isset($response['status']) && $response['status'] === 'error') {
            // Clear session before redirect
            session()->forget('tiket_data');
            return redirect()->back()->with('error', 'Gagal memproses ke Xendit: ' . ($response['message'] ?? 'Kesalahan tidak diketahui.'));
        }

        // Update ticket with payment data
        $tiket->update([
            'xendit_id' => $response['id'] ?? null,
            'payment_data' => json_encode($response)
        ]);

        // Clear session after successful payment creation
        session()->forget('tiket_data');

        // Redirect to payment result page
        return redirect()->to('wisata/payment/result?order_id=' . $kodeTicket);
    }

    public function wisata_payment_manual(Request $request)
    {
        if (!session('tiket_data')) {
            return redirect()->to('wisata');
        }

        $tiketData = session('tiket_data');
        $amount = $request->query('amount');
        
        $settingsPath = storage_path('app/settings.json');
        $settings = json_decode(file_get_contents($settingsPath), true);
        $bankAccounts = $settings['bank_accounts'] ?? [];

        return view('front.pages.wisata_payment_manual', compact('tiketData', 'amount', 'bankAccounts'));
    }

    public function wisata_payment_manual_submit(Request $request)
    {
        if (!session('tiket_data')) {
            return redirect()->to('wisata');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|max:2048'
        ]);

        $tiketData = session('tiket_data');

        // Upload bukti transfer
        $file = $request->file('bukti_transfer');
        $filename = time() . '_' . $file->getClientOriginalName();
        
        if (!file_exists(public_path('bukti_transfer'))) {
            mkdir(public_path('bukti_transfer'), 0777, true);
        }
        
        $file->move(public_path('bukti_transfer'), $filename);

        // Create ticket record
        $kodeTimestamp = time();
        $kodeRandom = strtoupper(\Illuminate\Support\Str::random(6));
        $kodeTicket = "TKT-{$kodeTimestamp}-{$kodeRandom}";

        $tiket = TiketWisata::create([
            'kode_tiket' => $kodeTicket,
            'id_objek_wisata' => $tiketData['id_objek_wisata'],
            'nama_pengunjung' => $tiketData['nama_pengunjung'] ?? null,
            'email' => $tiketData['email'] ?? null,
            'no_wa' => $tiketData['no_wa'] ?? null,
            'total_harga' => $tiketData['total_harga'],
            'tanggal_kunjungan' => $tiketData['tanggal_kunjungan'],
            'metode_pembelian' => 'online',
            'metode_pembayaran' => 'transfer_manual',
            'bukti_transfer' => $filename,
            'status_pembayaran' => 'pending',
            'status_verifikasi' => 'pending',
            'status_tiket' => 'belum_digunakan',
            'qr_code' => $kodeTicket,
            'aktif' => '1'
        ]);

        // Create tiket details
        foreach ($tiketData['kategori'] as $detail) {
            TiketDetail::create([
                'id_tiket' => $tiket->id_tiket,
                'id_kategori_tiket' => $detail['id_kategori_tiket'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                'subtotal' => $detail['subtotal']
            ]);
        }

        // Clear session
        session()->forget('tiket_data');

        return redirect()->to('wisata/payment/manual/success?id=' . $tiket->id_tiket);
    }

    public function wisata_payment_manual_success(Request $request)
    {
        $id = $request->query('id');
        $tiket = TiketWisata::with(['objekWisata', 'details.kategoriTiket'])->findOrFail($id);
        return view('front.pages.wisata_payment_manual_success', compact('tiket'));
    }

    public function wisata_payment_result(Request $request)
    {
        $order_id = $request->query('order_id');
        
        $tiket = TiketWisata::where('kode_tiket', $order_id)->first();
        
        if (!$tiket || !$tiket->payment_data) {
            return redirect()->to('wisata')->with('error', 'Transaksi tidak ditemukan.');
        }

        $payment_data = json_decode($tiket->payment_data, true);
        $method = $tiket->metode_pembayaran;
        
        $xendit = new \App\Services\XenditService();
        $is_sandbox = $xendit->isSandbox();
        
        $village = $this->getVillageData();
        
        // Get payment channel info from database
        $channel = PaymentChannel::where('code', $method)->first();

        return view('front.pages.wisata_payment_result', compact('tiket', 'payment_data', 'method', 'village', 'order_id', 'is_sandbox', 'channel'));
    }

    public function wisata_payment_status(Request $request)
    {
        $order_id = $request->query('order_id');
        $tiket = TiketWisata::where('kode_tiket', $order_id)->first();

        if (!$tiket) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $tiket->status_pembayaran,
        ]);
    }

    public function wisata_payment_simulate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        $xendit = new \App\Services\XenditService();
        
        if (!$xendit->isSandbox()) {
            return response()->json(['status' => 'error', 'message' => 'Simulation only allowed in Sandbox mode'], 403);
        }

        $order_id = $request->order_id;
        $amount = $request->amount;

        $tiket = TiketWisata::where('kode_tiket', $order_id)->first();
        if (!$tiket) {
            return response()->json(['status' => 'error', 'message' => 'Tiket not found'], 404);
        }

        $payment_data = json_decode($tiket->payment_data, true);
        
        // If it's VA, call Xendit Simulator API
        if (str_contains($tiket->metode_pembayaran, '_VA')) {
            $external_id = $payment_data['external_id'] ?? null;
            if ($external_id) {
                $simResponse = $xendit->simulateVAPayment($external_id, $amount);
                \Illuminate\Support\Facades\Log::info("Xendit VA Simulation Triggered for {$external_id}:", ['response' => $simResponse]);
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Permintaan simulasi telah dikirim ke Xendit. Mohon tunggu beberapa saat sampai webhook diterima sistem.'
                ]);
            } else {
                \Illuminate\Support\Facades\Log::warning("Xendit Simulation Skipped: No external_id found for ticket #{$order_id}");
                return response()->json(['status' => 'error', 'message' => 'ID Pembayaran tidak ditemukan untuk simulasi.'], 400);
            }
        }

        // If it's E-Wallet or QRIS, simulation happens on the Mock Page
        if (in_array($tiket->metode_pembayaran, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY', 'QRIS', 'ID_QRIS'])) {
            return response()->json([
                'status' => 'redirect_to_checkout',
                'message' => 'Untuk simulasi E-Wallet/QRIS, silakan gunakan tombol "Buka Aplikasi Pembayaran" untuk menuju ke halaman Simulator resmi Xendit.'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Simulasi belum tersedia untuk metode ini.'], 400);
    }

    public function wisata_tiket_success(Request $request)
    {
        $order_id = $request->query('order_id');
        
        $tiket = TiketWisata::with(['objekWisata', 'details.kategoriTiket'])
            ->where('kode_tiket', $order_id)
            ->firstOrFail();
        
        $village = $this->getVillageData();
        
        return view('front.pages.wisata_tiket_success', compact('tiket', 'village'));
    }

    public function wisata_tiket_download($kode)
    {
        try {
            $tiket = TiketWisata::with(['objekWisata', 'details.kategoriTiket'])
                ->where('kode_tiket', $kode)
                ->first();
            
            if (!$tiket) {
                \Log::error("Tiket not found: {$kode}");
                return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
            }
            
            // Check if payment is completed
            if ($tiket->status_pembayaran !== 'completed') {
                \Log::warning("Tiket payment not completed: {$kode}, status: {$tiket->status_pembayaran}");
                return redirect()->back()->with('error', 'Tiket belum dapat didownload. Selesaikan pembayaran terlebih dahulu.');
            }
            
            $village = $this->getVillageData();
            
            \Log::info("Generating PDF for ticket: {$kode}");
            
            // Fetch QR code image and convert to base64
            try {
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($tiket->qr_code);
                $qrImageData = @file_get_contents($qrUrl);
                
                if ($qrImageData !== false) {
                    $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrImageData);
                } else {
                    // Fallback: no QR code
                    $qrCodeBase64 = null;
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching QR code: " . $e->getMessage());
                $qrCodeBase64 = null;
            }
            
            $pdf = Pdf::loadView('pdf.tiket_wisata', compact('tiket', 'village', 'qrCodeBase64'));
            return $pdf->download('Tiket_' . $kode . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error("Error downloading ticket PDF: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
        }
    }

    public function wisata_check_availability(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required|exists:tb_objek_wisata,id_objek_wisata',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030'
        ]);

        $objek = ObjekWisata::findOrFail($request->id_objek_wisata);
        $batas = $objek->batas_tiket_harian;

        // If no limit, all dates available
        if (!$batas) {
            return response()->json(['unlimited' => true]);
        }

        // Get sold tickets per day for the month
        $startDate = sprintf('%04d-%02d-01', $request->year, $request->month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $soldPerDay = TiketDetail::query()
            ->join('tb_tiket_wisata', 'tb_tiket_wisata.id_tiket', '=', 'tb_tiket_detail.id_tiket')
            ->where('tb_tiket_wisata.id_objek_wisata', $request->id_objek_wisata)
            ->whereIn('tb_tiket_wisata.status_pembayaran', ['paid', 'completed', 'pending'])
            ->whereBetween('tb_tiket_wisata.tanggal_kunjungan', [$startDate, $endDate])
            ->selectRaw('tb_tiket_wisata.tanggal_kunjungan, SUM(tb_tiket_detail.jumlah) as total_tiket')
            ->groupBy('tb_tiket_wisata.tanggal_kunjungan')
            ->pluck('total_tiket', 'tanggal_kunjungan')
            ->toArray();

        $availability = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);
        while ($current <= $end) {
            $dateStr = date('Y-m-d', $current);
            $sold = $soldPerDay[$dateStr] ?? 0;
            $availability[$dateStr] = [
                'sold' => (int)$sold,
                'limit' => $batas,
                'available' => $batas - (int)$sold
            ];
            $current = strtotime('+1 day', $current);
        }

        return response()->json(['unlimited' => false, 'dates' => $availability, 'limit' => $batas]);
    }

    public function agenda(Request $request)
    {
        $selected_category = $request->get('kategori', 'all');
        $village = $this->getVillageData();
        
        $query = \App\Models\Agenda::with('kategori')
            ->where('aktif', '1')
            ->where('status_agenda', 'Publish');
            
        if ($selected_category !== 'all') {
            $query->where('id_kategori_agenda', $selected_category);
        }
        
        $agendas = $query->orderBy('tanggal_agenda', 'desc')->orderBy('waktu_agenda', 'asc')->get();
        $kategori_list = \App\Models\KategoriAgenda::where('aktif', '1')->orderBy('nama_kategori', 'asc')->get();
        
        return view('front.pages.agenda', compact('agendas', 'village', 'kategori_list', 'selected_category'));
    }

    public function krama_tamiu()
    {
        $village = $this->getVillageData();
        $banjarList = \App\Models\Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        
        // Stats per banjar
        $banjarStats = [];
        foreach ($banjarList as $banjar) {
            $count = \App\Models\Pendatang::where('id_data_banjar', $banjar->id_data_banjar)
                ->where('aktif', '1')
                ->where('status', 'aktif')
                ->count();
            $banjarStats[$banjar->id_data_banjar] = $count;
        }
        
        $totalKramaTamiu = \App\Models\Pendatang::where('aktif', '1')->where('status', 'aktif')->count();
        
        return view('front.pages.krama_tamiu', compact('village', 'banjarList', 'banjarStats', 'totalKramaTamiu'));
    }
    
    public function krama_tamiu_register()
    {
        $village = $this->getVillageData();
        $banjarList = \App\Models\Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        return view('front.pages.krama_tamiu_register', compact('village', 'banjarList'));
    }
    
    public function krama_tamiu_register_submit(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_pendatang,nik',
            'asal' => 'required|string|max:200',
            'no_hp' => 'required|string|max:20',
            'alamat_tinggal' => 'required|string',
            'id_data_banjar' => 'required|integer|exists:tb_data_banjar,id_data_banjar',
            'tinggal_dari' => 'nullable|date',
            'tinggal_sampai' => 'nullable|date|after_or_equal:tinggal_dari',
            'tinggal_belum_yakin' => 'nullable|boolean'
        ]);
        
        $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
        $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
        
        \App\Models\Pendatang::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'asal' => $request->asal,
            'no_hp' => $request->no_hp,
            'alamat_tinggal' => $request->alamat_tinggal,
            'id_data_banjar' => $request->id_data_banjar,
            'punia_rutin_bulanan' => $puniaGlobal,
            'use_global_punia' => true,
            'tinggal_dari' => $request->tinggal_dari,
            'tinggal_sampai' => $request->tinggal_sampai,
            'tinggal_belum_yakin' => $request->has('tinggal_belum_yakin'),
            'status' => 'aktif',
            'aktif' => '1',
        ]);
        
        return redirect()->route('public.krama_tamiu')->with('success', 'Pendaftaran berhasil! Data Anda akan diverifikasi oleh Kelian Banjar.');
    }

}
