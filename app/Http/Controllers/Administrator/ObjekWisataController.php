<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\KategoriTiket;
use Illuminate\Support\Facades\Storage;

class ObjekWisataController extends Controller
{
    public function index()
    {
        $objekWisata = ObjekWisata::orderBy('created_at', 'desc')->get();
        return view('admin.pages.objek_wisata.index', compact('objekWisata'));
    }

    public function create()
    {
        return view('admin.pages.objek_wisata.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_objek' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'harga_tiket' => 'nullable|numeric',
            'foto.*' => 'nullable|image|max:2048',
            'kategori_aktif' => 'required|array|min:1',
            'harga' => 'required|array'
        ]);

        $data = $request->except(['foto', 'kategori_aktif', 'harga']);
        
        // Set kapasitas_harian to null if empty
        if (empty($data['kapasitas_harian'])) {
            $data['kapasitas_harian'] = null;
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

        // Create kategori tiket
        $urutan = 1;
        foreach ($request->kategori_aktif as $key) {
            if (isset($kategoriMapping[$key]) && isset($request->harga[$key]) && $request->harga[$key] > 0) {
                KategoriTiket::create([
                    'id_objek_wisata' => $objek->id_objek_wisata,
                    'nama_kategori' => $kategoriMapping[$key]['nama'],
                    'tipe_kategori' => $kategoriMapping[$key]['tipe'],
                    'harga' => $request->harga[$key],
                    'deskripsi' => null,
                    'urutan' => $urutan,
                    'aktif' => 1
                ]);
                $urutan++;
            }
        }

        // Check if request from kelian
        if (request()->is('administrator/kelian/*')) {
            return redirect()->to('administrator/kelian/tiket')->with('success', 'Objek wisata berhasil ditambahkan');
        }

        return redirect()->to('administrator/objek_wisata')->with('success', 'Objek wisata berhasil ditambahkan');
    }

    public function edit($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        return view('admin.pages.objek_wisata.edit', compact('objek'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_objek' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'harga_tiket' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048'
        ]);

        $objek = ObjekWisata::findOrFail($id);
        $data = $request->except('foto');
        
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
        $objekWisata = ObjekWisata::orderBy('created_at', 'desc')->get();
        return view('backend.kelian.tiket_objek', compact('objekWisata'));
    }

    public function detail_kelian($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        return view('backend.kelian.tiket_objek_detail', compact('objek'));
    }

    public function create_kelian()
    {
        return view('backend.kelian.tiket_objek_create');
    }

    public function edit_kelian($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        return view('backend.kelian.tiket_objek_edit', compact('objek'));
    }

    // Kategori Tiket Management
    public function store_kategori(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required|exists:tb_objek_wisata,id_objek_wisata',
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:orang,kendaraan,paket',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        // Get max urutan
        $maxUrutan = KategoriTiket::where('id_objek_wisata', $request->id_objek_wisata)->max('urutan') ?? 0;

        KategoriTiket::create([
            'id_objek_wisata' => $request->id_objek_wisata,
            'nama_kategori' => $request->nama_kategori,
            'tipe_kategori' => $request->tipe_kategori,
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
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori = KategoriTiket::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'tipe_kategori' => $request->tipe_kategori,
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
