<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendatang;
use App\Models\PuniaPendatang;
use App\Models\AcaraPunia;
use App\Models\Banjar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PendatangController extends Controller
{
    public function indexBendesa(Request $request)
    {
        $query = Pendatang::with(['banjar', 'puniaPendatang'])->where('aktif', '1');

        if ($request->filled('banjar')) {
            $query->where('id_data_banjar', $request->banjar);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pendatangList = $query->orderBy('created_at', 'desc')->get();
        $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        $acaraList = AcaraPunia::where('aktif', '1')->orderBy('created_at', 'desc')->get();
        $totalAktif = Pendatang::where('aktif', '1')->where('status', 'aktif')->count();
        $totalPendatang = Pendatang::where('aktif', '1')->count();
        $totalBelumBayar = PuniaPendatang::where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();

        return view('admin.pages.pendatang.index', compact(
            'pendatangList', 'banjarList', 'acaraList',
            'totalAktif', 'totalPendatang', 'totalBelumBayar'
        ));
    }

    public function index()
    {
        $acaraList = AcaraPunia::where('aktif', '1')->orderBy('created_at', 'desc')->get();
        return view('backend.kelian.pendatang', compact('acaraList'));
    }
    
    public function setting()
    {
        return view('backend.kelian.pendatang_setting');
    }
    
    public function updateSetting(Request $request)
    {
        $request->validate([
            'punia_pendatang_global' => 'required|numeric|min:0'
        ]);
        
        $settingsPath = storage_path('app/settings.json');
        $settings = json_decode(file_get_contents($settingsPath), true);
        $settings['punia_pendatang_global'] = $request->punia_pendatang_global;
        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
        
        return redirect()->back()->with('success', 'Pengaturan punia global berhasil diupdate');
    }
    
    public function updatePuniaSetting(Request $request, $id)
    {
        $useGlobal = $request->has('use_global_punia') && $request->use_global_punia == '1';
        
        $request->validate([
            'punia_rutin_bulanan' => $useGlobal ? 'nullable' : 'required|numeric|min:0'
        ]);
        
        $pendatang = Pendatang::findOrFail($id);
        
        $data = [
            'use_global_punia' => $useGlobal
        ];
        
        // If using global setting, get from settings.json
        if ($useGlobal) {
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $data['punia_rutin_bulanan'] = $settings['punia_pendatang_global'] ?? 0;
        } else {
            $data['punia_rutin_bulanan'] = $request->punia_rutin_bulanan;
        }
        
        $pendatang->update($data);
        
        return redirect()->back()->with('success', 'Setting punia berhasil diupdate');
    }
    
    public function create()
    {
        $banjarList = \App\Models\Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        return view('backend.kelian.pendatang_create', compact('banjarList'));
    }
    
    public function createAcara()
    {
        return view('backend.kelian.pendatang_create_acara');
    }
    
    public function detail($id)
    {
        $pendatang = Pendatang::with(['puniaPendatang' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return view('backend.kelian.pendatang_detail', compact('pendatang'));
    }
    
    public function edit($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        $banjarList = \App\Models\Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();
        return view('backend.kelian.pendatang_edit', compact('pendatang', 'banjarList'));
    }
    
    public function addPunia($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        return view('backend.kelian.pendatang_add_punia', compact('pendatang'));
    }
    
    public function bayarForm($id)
    {
        $punia = PuniaPendatang::with('pendatang')->findOrFail($id);
        return view('backend.kelian.pendatang_bayar', compact('punia'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_pendatang,nik',
            'asal' => 'required|string|max:200',
            'no_hp' => 'required|string|max:20',
            'alamat_tinggal' => 'nullable|string',
            'id_data_banjar' => 'nullable|integer|exists:tb_data_banjar,id_data_banjar',
            'use_global_punia' => 'required|boolean',
            'punia_rutin_bulanan' => 'required_if:use_global_punia,0|nullable|numeric|min:0'
        ]);
        
        $data = $request->all();
        
        // If using global setting, get from settings.json
        if ($request->use_global_punia) {
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $data['punia_rutin_bulanan'] = $settings['punia_pendatang_global'] ?? 0;
        }
        
        $pendatang = Pendatang::create($data);
        
        // Auto-generate tagihan punia untuk bulan ini jika nominal > 0
        if ($pendatang->punia_rutin_bulanan > 0) {
            $bulanTahun = now()->format('Y-m');
            
            PuniaPendatang::create([
                'id_pendatang' => $pendatang->id_pendatang,
                'jenis_punia' => 'rutin',
                'periode_rutin' => 'bulanan',
                'bulan_tahun' => $bulanTahun,
                'nominal' => $pendatang->punia_rutin_bulanan,
                'keterangan' => 'Punia rutin bulan ' . now()->translatedFormat('F Y'),
                'petugas_id' => auth()->id()
            ]);
        }
        
        return redirect(url('administrator/kelian/pendatang'))->with('success', 'Data pendatang berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $pendatang = Pendatang::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:tb_pendatang,nik,'.$id.',id_pendatang',
            'asal' => 'required|string|max:200',
            'no_hp' => 'required|string|max:20',
            'alamat_tinggal' => 'nullable|string',
            'id_data_banjar' => 'nullable|integer|exists:tb_data_banjar,id_data_banjar',
            'punia_rutin_bulanan' => 'required|numeric|min:0'
        ]);
        
        $useGlobal = $request->has('use_global_punia') && $request->use_global_punia == '1';
        $data = $request->only(['nama', 'nik', 'asal', 'no_hp', 'alamat_tinggal', 'id_data_banjar', 'punia_rutin_bulanan']);
        $data['use_global_punia'] = $useGlobal;
        
        if ($useGlobal) {
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $data['punia_rutin_bulanan'] = $settings['punia_pendatang_global'] ?? 0;
        }
        
        $pendatang->update($data);
        
        return redirect()->to('administrator/kelian/pendatang/detail/'.$id)->with('success', 'Data pendatang berhasil diupdate');
    }
    
    public function delete($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        $pendatang->delete();
        
        return redirect(url('administrator/kelian/pendatang'))->with('success', 'Data pendatang berhasil dihapus');
    }
    
    public function toggle($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        $pendatang->status = $pendatang->status === 'aktif' ? 'nonaktif' : 'aktif';
        $pendatang->save();
        
        return redirect()->back()->with('success', 'Status pendatang berhasil diubah');
    }
    
    public function generateTagihan($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        
        if ($pendatang->punia_rutin_bulanan <= 0) {
            return redirect()->back()->with('error', 'Nominal punia rutin bulanan belum diatur');
        }
        
        $bulanTahun = now()->format('Y-m');
        
        // Check if tagihan already exists
        $exists = PuniaPendatang::where('id_pendatang', $id)
            ->where('jenis_punia', 'rutin')
            ->where('bulan_tahun', $bulanTahun)
            ->where('aktif', '1')
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('error', 'Tagihan bulan ini sudah ada');
        }
        
        PuniaPendatang::create([
            'id_pendatang' => $id,
            'jenis_punia' => 'rutin',
            'periode_rutin' => 'bulanan',
            'bulan_tahun' => $bulanTahun,
            'nominal' => $pendatang->punia_rutin_bulanan,
            'keterangan' => 'Punia rutin bulan ' . now()->translatedFormat('F Y'),
            'petugas_id' => auth()->id()
        ]);
        
        return redirect()->back()->with('success', 'Tagihan punia bulan ini berhasil dibuat');
    }
    
    public function kartuPunia($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        $selectedYear = (int)request()->get('year', date('Y'));
        
        // Fetch all regular payments for the selected year
        $payments = PuniaPendatang::where('id_pendatang', $id)
            ->where('jenis_punia', 'rutin')
            ->where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where('bulan_tahun', 'LIKE', $selectedYear . '-%')
            ->get()
            ->keyBy(function($item) {
                // Extract month as integer (1-12)
                return (int)substr($item->bulan_tahun, 5, 2);
            });
            
        $totalKontribusi = $payments->sum('nominal');
        $currentDateFormatted = now()->translatedFormat('d M');
        
        return view('backend.kelian.pendatang_kartu_punia', compact('pendatang', 'payments', 'selectedYear', 'totalKontribusi', 'currentDateFormatted'));
    }

    public function printKartuPunia($id)
    {
        $pendatang = Pendatang::findOrFail($id);
        $year = (int)request()->get('year', date('Y'));

        $payments = PuniaPendatang::where('id_pendatang', $id)
            ->where('jenis_punia', 'rutin')
            ->where('aktif', '1')
            ->where('status_pembayaran', 'lunas')
            ->where('bulan_tahun', 'LIKE', $year . '-%')
            ->get()
            ->keyBy(function($item) {
                return (int)substr($item->bulan_tahun, 5, 2);
            });

        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $settingsPath = storage_path('app/settings.json');
        $village = file_exists($settingsPath)
            ? json_decode(file_get_contents($settingsPath), true)
            : ['name' => 'SPDA'];

        $data = compact('pendatang', 'year', 'months', 'payments', 'village', 'currentYear', 'currentMonth');

        $pdf = Pdf::loadView('pdf.kartu_punia_pendatang', $data);
        return $pdf->download('Kartu_Punia_' . $pendatang->nama . '_' . $year . '.pdf');
    }
    
    public function bayarKartuPunia(Request $request)
    {
        $request->validate([
            'id_pendatang' => 'required|exists:tb_pendatang,id_pendatang',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'metode_pembayaran' => 'required|in:cash,qris'
        ]);
        
        $pendatang = Pendatang::findOrFail($request->id_pendatang);
        $bulanTahun = $request->tahun . '-' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
        
        // Check if tagihan exists
        $punia = PuniaPendatang::where('id_pendatang', $request->id_pendatang)
            ->where('jenis_punia', 'rutin')
            ->where('bulan_tahun', $bulanTahun)
            ->where('aktif', '1')
            ->first();
        
        if (!$punia) {
            // Create tagihan if not exists
            $punia = PuniaPendatang::create([
                'id_pendatang' => $request->id_pendatang,
                'jenis_punia' => 'rutin',
                'periode_rutin' => 'bulanan',
                'bulan_tahun' => $bulanTahun,
                'nominal' => $pendatang->punia_rutin_bulanan,
                'keterangan' => 'Punia rutin bulan ' . \Carbon\Carbon::createFromFormat('Y-m', $bulanTahun)->translatedFormat('F Y'),
                'petugas_id' => auth()->id()
            ]);
        }
        
        // Update payment status
        $punia->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'petugas_id' => auth()->id()
        ]);
        
        return redirect()->back()->with('success', 'Pembayaran punia berhasil dicatat');
    }
    
    // Punia Management
    public function storePunia(Request $request)
    {
        $request->validate([
            'id_pendatang' => 'required|exists:tb_pendatang,id_pendatang',
            'jenis_punia' => 'required|in:rutin,acara',
            'nama_acara' => 'required_if:jenis_punia,acara',
            'periode_rutin' => 'required_if:jenis_punia,rutin|in:bulanan,tahunan',
            'bulan_tahun' => 'required_if:jenis_punia,rutin',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string'
        ]);
        
        PuniaPendatang::create([
            'id_pendatang' => $request->id_pendatang,
            'jenis_punia' => $request->jenis_punia,
            'nama_acara' => $request->nama_acara,
            'periode_rutin' => $request->periode_rutin,
            'bulan_tahun' => $request->bulan_tahun,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'petugas_id' => auth()->id()
        ]);
        
        return redirect()->to('administrator/kelian/pendatang/detail/'.$request->id_pendatang)->with('success', 'Tagihan punia berhasil ditambahkan');
    }
    
    public function bayarPunia(Request $request, $id)
    {
        $punia = PuniaPendatang::findOrFail($id);
        
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,qris'
        ]);
        
        $punia->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'petugas_id' => auth()->id()
        ]);
        
        return redirect()->to('administrator/kelian/pendatang/detail/'.$punia->id_pendatang)->with('success', 'Pembayaran punia berhasil dicatat');
    }
    
    public function deletePunia($id)
    {
        $punia = PuniaPendatang::findOrFail($id);
        $idPendatang = $punia->id_pendatang;
        $punia->delete();
        
        return redirect()->to('administrator/kelian/pendatang/detail/'.$idPendatang)->with('success', 'Tagihan punia berhasil dihapus');
    }
    
    // Acara Management
    public function storeAcara(Request $request)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_acara' => 'nullable|date',
            'batas_pembayaran' => 'nullable|date'
        ]);
        
        $acara = AcaraPunia::create([
            'nama_acara' => $request->nama_acara,
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
            'tanggal_acara' => $request->tanggal_acara,
            'batas_pembayaran' => $request->batas_pembayaran,
            'created_by' => auth()->id()
        ]);
        
        // Auto-assign to all active pendatang
        $pendatangAktif = Pendatang::where('status', 'aktif')->where('aktif', '1')->get();
        
        foreach ($pendatangAktif as $pendatang) {
            PuniaPendatang::create([
                'id_pendatang' => $pendatang->id_pendatang,
                'id_acara_punia' => $acara->id_acara_punia,
                'jenis_punia' => 'acara',
                'nama_acara' => $acara->nama_acara,
                'nominal' => $acara->nominal,
                'petugas_id' => auth()->id()
            ]);
        }
        
        return redirect(url('administrator/kelian/pendatang'))->with('success', 'Acara berhasil dibuat dan tagihan telah ditambahkan ke semua pendatang aktif');
    }
    
    public function deleteAcara($id)
    {
        $acara = AcaraPunia::findOrFail($id);
        // Cascade will delete all related punia_pendatang
        $acara->delete();
        
        return redirect()->back()->with('success', 'Acara dan semua tagihan terkait berhasil dihapus');
    }
    
    public function toggleAcara($id)
    {
        $acara = AcaraPunia::findOrFail($id);
        $acara->status = $acara->status === 'aktif' ? 'selesai' : 'aktif';
        $acara->save();
        
        return redirect()->back()->with('success', 'Status acara berhasil diubah');
    }
}
