<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\KategoriTiket;
use Illuminate\Support\Facades\Storage;

class ObjekWisataController extends Controller
{
    public function index(Request $request)
    {
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');

        $dateFilter = function($q) use ($tanggalDari, $tanggalSampai) {
            $q->where('status_pembayaran', 'completed');
            if ($tanggalDari) {
                $q->whereDate('created_at', '>=', $tanggalDari);
            }
            if ($tanggalSampai) {
                $q->whereDate('created_at', '<=', $tanggalSampai);
            }
        };

        $query = ObjekWisata::with(['banjar', 'tiket' => function($q) use ($dateFilter) {
                $dateFilter($q);
                $q->with('details.kategoriTiket')->orderBy('created_at', 'desc');
            }])
            ->withSum(['tiket as total_pemasukan' => $dateFilter], 'total_harga')
            ->withCount(['tiket as total_tiket_terjual' => $dateFilter]);

        // Kelian only sees objects from their banjar
        if (auth()->user()->id_level != config('myconfig.level.bendesa', 1)) {
            $banjar = auth()->user()->banjar;
            $idBanjar = $banjar ? $banjar->id_data_banjar : 0;
            $query->where('id_data_banjar', $idBanjar);
        }

        $objekWisata = $query->orderBy('created_at', 'desc')->get();
        return view('admin.pages.objek_wisata.index', compact('objekWisata', 'tanggalDari', 'tanggalSampai'));
    }

    public function create()
    {
        $banjar = \App\Models\Banjar::where('aktif', '1')->get();
        return view('admin.pages.objek_wisata.create', compact('banjar'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_objek' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'id_data_banjar' => 'required|exists:tb_data_banjar,id_data_banjar',
            'harga_tiket' => 'nullable|numeric',
            'foto.*' => 'nullable|image|max:2048'
        ];

        if ($request->has('kategori_aktif')) {
            $rules['kategori_aktif'] = 'required|array|min:1';
        }

        // Dual pricing mode uses different field names
        if ($request->input('bedakan_harga') === '1') {
            // At least one of the dual arrays must be present
        } elseif ($request->has('kategori_aktif')) {
            $rules['harga'] = 'required|array';
        }

        $request->validate($rules);

        $data = $request->except(['foto', 'kategori_aktif', 'kategori_aktif_local', 'kategori_aktif_wna', 'kategori_aktif_dual', 'harga', 'harga_local', 'harga_wna', 'market_type', 'tipe_kategori_utama', 'custom_nama_kendaraan', 'custom_harga_kendaraan', 'bedakan_harga']);
        
        // Set kapasitas_harian to null if empty
        if (empty($data['kapasitas_harian'])) {
            $data['kapasitas_harian'] = null;
        }
        
        // Set batas_tiket_harian to null if empty (unlimited)
        if (empty($data['batas_tiket_harian'])) {
            $data['batas_tiket_harian'] = null;
        }
        
        if ($request->hasFile('foto')) {
            // Create directory if not exists
            if (!file_exists(public_path('storage/wisata'))) {
                mkdir(public_path('storage/wisata'), 0777, true);
            }
            
            $files = $request->file('foto');
            $firstFile = true;
            
            foreach ($files as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/wisata'), $filename);
                
                // Save first photo to main foto field
                if ($firstFile) {
                    $data['foto'] = $filename;
                    $firstFile = false;
                }
            }
        }

        $data['aktif'] = '1';
        $data['status'] = 'aktif';

        $objek = ObjekWisata::create($data);

        // Mapping kategori
        $kategoriMapping = [
            'umum' => ['nama' => 'Tiket Umum', 'tipe' => 'orang'],
            'dewasa' => ['nama' => 'Dewasa', 'tipe' => 'orang'],
            'anak' => ['nama' => 'Anak-anak', 'tipe' => 'orang'],
            'balita' => ['nama' => 'Balita', 'tipe' => 'orang'],
            'lansia' => ['nama' => 'Lansia', 'tipe' => 'orang'],
            'pelajar' => ['nama' => 'Pelajar/Mahasiswa', 'tipe' => 'orang'],
            'motor' => ['nama' => 'Motor', 'tipe' => 'kendaraan'],
            'mobil' => ['nama' => 'Mobil', 'tipe' => 'kendaraan'],
            'bus' => ['nama' => 'Bus', 'tipe' => 'kendaraan'],
            'truk' => ['nama' => 'Truk', 'tipe' => 'kendaraan']
        ];

        // Create kategori tiket if present
        $bedakanHarga = $request->input('bedakan_harga') === '1';
        $urutan = 1;

        if ($bedakanHarga && ($request->has('kategori_aktif_local') || $request->has('kategori_aktif_wna') || $request->has('kategori_aktif_dual'))) {
            // Dual pricing mode: create separate Local and WNA records

            // Handle "sama semua usia" dual mode (kategori_aktif_local + kategori_aktif_wna)
            if ($request->has('kategori_aktif_local')) {
                foreach ($request->kategori_aktif_local as $key) {
                    if (isset($kategoriMapping[$key]) && isset($request->harga_local[$key]) && $request->harga_local[$key] > 0) {
                        KategoriTiket::create([
                            'id_objek_wisata' => $objek->id_objek_wisata,
                            'nama_kategori' => $kategoriMapping[$key]['nama'],
                            'tipe_kategori' => $kategoriMapping[$key]['tipe'],
                            'market_type' => 'local',
                            'harga' => $request->harga_local[$key],
                            'deskripsi' => null,
                            'urutan' => $urutan,
                            'aktif' => 1
                        ]);
                        $urutan++;
                    }
                }
            }
            if ($request->has('kategori_aktif_wna')) {
                foreach ($request->kategori_aktif_wna as $key) {
                    if (isset($kategoriMapping[$key]) && isset($request->harga_wna[$key]) && $request->harga_wna[$key] > 0) {
                        KategoriTiket::create([
                            'id_objek_wisata' => $objek->id_objek_wisata,
                            'nama_kategori' => $kategoriMapping[$key]['nama'],
                            'tipe_kategori' => $kategoriMapping[$key]['tipe'],
                            'market_type' => 'wna',
                            'harga' => $request->harga_wna[$key],
                            'deskripsi' => null,
                            'urutan' => $urutan,
                            'aktif' => 1
                        ]);
                        $urutan++;
                    }
                }
            }

            // Handle "berbeda usia" dual mode (kategori_aktif_dual)
            if ($request->has('kategori_aktif_dual')) {
                foreach ($request->kategori_aktif_dual as $key) {
                    if (!isset($kategoriMapping[$key])) continue;
                    
                    // Create Local record
                    if (isset($request->harga_local[$key]) && $request->harga_local[$key] > 0) {
                        KategoriTiket::create([
                            'id_objek_wisata' => $objek->id_objek_wisata,
                            'nama_kategori' => $kategoriMapping[$key]['nama'],
                            'tipe_kategori' => $kategoriMapping[$key]['tipe'],
                            'market_type' => 'local',
                            'harga' => $request->harga_local[$key],
                            'deskripsi' => null,
                            'urutan' => $urutan,
                            'aktif' => 1
                        ]);
                        $urutan++;
                    }
                    
                    // Create WNA record
                    if (isset($request->harga_wna[$key]) && $request->harga_wna[$key] > 0) {
                        KategoriTiket::create([
                            'id_objek_wisata' => $objek->id_objek_wisata,
                            'nama_kategori' => $kategoriMapping[$key]['nama'],
                            'tipe_kategori' => $kategoriMapping[$key]['tipe'],
                            'market_type' => 'wna',
                            'harga' => $request->harga_wna[$key],
                            'deskripsi' => null,
                            'urutan' => $urutan,
                            'aktif' => 1
                        ]);
                        $urutan++;
                    }
                }
            }
        } elseif ($request->has('kategori_aktif')) {
            // Single pricing mode (market_type = 'all')
            foreach ($request->kategori_aktif as $key) {
                if (isset($kategoriMapping[$key]) && isset($request->harga[$key]) && $request->harga[$key] > 0) {
                    KategoriTiket::create([
                        'id_objek_wisata' => $objek->id_objek_wisata,
                        'nama_kategori' => $kategoriMapping[$key]['nama'],
                        'tipe_kategori' => $kategoriMapping[$key]['tipe'],
                        'market_type' => $request->market_type[$key] ?? 'all',
                        'harga' => $request->harga[$key],
                        'deskripsi' => null,
                        'urutan' => $urutan,
                        'aktif' => 1
                    ]);
                    $urutan++;
                }
            }

            // Handle multiple custom kendaraan
            if ($request->has('custom_nama_kendaraan')) {
                $customNames = $request->input('custom_nama_kendaraan');
                $customPrices = $request->input('custom_harga_kendaraan', []);
                foreach ($customNames as $i => $name) {
                    $price = $customPrices[$i] ?? 0;
                    if (!empty($name) && $price > 0) {
                        KategoriTiket::create([
                            'id_objek_wisata' => $objek->id_objek_wisata,
                            'nama_kategori' => $name,
                            'tipe_kategori' => 'kendaraan',
                            'market_type' => 'all',
                            'harga' => $price,
                            'deskripsi' => null,
                            'urutan' => $urutan,
                            'aktif' => 1
                        ]);
                        $urutan++;
                    }
                }
            }
        }

        // Check if request from kelian
        if (request()->is('administrator/kelian/*')) {
            return redirect()->to('administrator/kelian/tiket')->with('success', 'Objek wisata berhasil ditambahkan');
        }

        return redirect()->to('administrator/objek_wisata/edit/'.$objek->id_objek_wisata)->with('success', 'Informasi Dasar berhasil dibuat! Silakan tambahkan Kategori Tiket untuk melengkapi.');
    }

    public function edit($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        $banjar = \App\Models\Banjar::where('aktif', '1')->get();
        return view('admin.pages.objek_wisata.edit', compact('objek', 'banjar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_objek' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'id_data_banjar' => 'required|exists:tb_data_banjar,id_data_banjar',
            'harga_tiket' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048'
        ]);

        $objek = ObjekWisata::findOrFail($id);
        $data = $request->except('foto');
        
        // Convert empty batas_tiket_harian to null
        if (empty($data['batas_tiket_harian'])) {
            $data['batas_tiket_harian'] = null;
        }
        
        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($objek->foto && file_exists(public_path('storage/wisata/' . $objek->foto))) {
                unlink(public_path('storage/wisata/' . $objek->foto));
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/wisata'), $filename);
            $data['foto'] = $filename;
        }

        $objek->update($data);

        // Check if request from kelian
        if (request()->is('administrator/kelian/*')) {
            return redirect()->to('administrator/kelian/tiket/objek/detail/'.$id)->with('success', 'Objek wisata berhasil diupdate');
        }

        return redirect()->to('administrator/objek_wisata')->with('success', 'Objek wisata berhasil diupdate');
    }

    public function destroy($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        
        // Soft delete by setting aktif to 0
        $objek->update(['aktif' => '0']);

        // Check if request from kelian
        if (request()->is('administrator/kelian/*')) {
            return redirect()->to('administrator/kelian/tiket')->with('success', 'Objek wisata berhasil dihapus');
        }

        return redirect()->to('administrator/objek_wisata')->with('success', 'Objek wisata berhasil dihapus');
    }

    public function toggle_status($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        $objek->update([
            'status' => $objek->status === 'aktif' ? 'nonaktif' : 'aktif'
        ]);

        // Check if request from kelian
        if (request()->is('administrator/kelian/*')) {
            return redirect()->to('administrator/kelian/tiket/objek/detail/'.$id)->with('success', 'Status objek wisata berhasil diubah');
        }

        return redirect()->back()->with('success', 'Status objek wisata berhasil diubah');
    }

    // Kelian Mobile Views
    public function index_kelian()
    {
        if (auth()->user()->id_level == config('myconfig.level.bendesa', 1)) {
            $objekWisata = ObjekWisata::orderBy('created_at', 'desc')->get();
        } else {
            $idBanjar = auth()->user()->id_banjar ?? 0;
            $objekWisata = ObjekWisata::where('id_data_banjar', $idBanjar)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return view('backend.kelian.tiket_objek', compact('objekWisata'));
    }

    public function detail_kelian($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        return view('backend.kelian.tiket_objek_detail', compact('objek'));
    }

    public function create_kelian()
    {
        $banjar = \App\Models\Banjar::where('aktif', '1')->get();
        return view('backend.kelian.tiket_objek_create', compact('banjar'));
    }

    public function edit_kelian($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        $banjar = \App\Models\Banjar::where('aktif', '1')->get();
        return view('backend.kelian.tiket_objek_edit', compact('objek', 'banjar'));
    }

    // Kategori Tiket Management
    public function store_kategori(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required|exists:tb_objek_wisata,id_objek_wisata',
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:orang,kendaraan,paket',
            'market_type' => 'nullable|in:all,wna,local',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        // Get max urutan
        $maxUrutan = KategoriTiket::where('id_objek_wisata', $request->id_objek_wisata)->max('urutan') ?? 0;

        KategoriTiket::create([
            'id_objek_wisata' => $request->id_objek_wisata,
            'nama_kategori' => $request->nama_kategori,
            'tipe_kategori' => $request->tipe_kategori,
            'market_type' => $request->market_type ?? 'all',
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'urutan' => $maxUrutan + 1,
            'aktif' => 1
        ]);

        return redirect()->back()->with('success', 'Kategori tiket berhasil ditambahkan');
    }

    public function update_kategori(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:orang,kendaraan,paket',
            'market_type' => 'nullable|in:all,wna,local',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori = KategoriTiket::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'tipe_kategori' => $request->tipe_kategori,
            'market_type' => $request->market_type ?? 'all',
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->back()->with('success', 'Kategori tiket berhasil diupdate');
    }

    public function delete_kategori($id)
    {
        $kategori = KategoriTiket::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori tiket berhasil dihapus');
    }
}
