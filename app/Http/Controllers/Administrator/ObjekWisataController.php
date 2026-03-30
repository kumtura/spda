<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
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
            'harga_tiket' => 'required|numeric',
            'foto' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('foto');
        
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Create directory if not exists
            if (!file_exists(public_path('storage/wisata'))) {
                mkdir(public_path('storage/wisata'), 0777, true);
            }
            
            $file->move(public_path('storage/wisata'), $filename);
            $data['foto'] = $filename;
        }

        $data['aktif'] = '1';
        $data['status'] = 'aktif';

        ObjekWisata::create($data);

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
            'harga_tiket' => 'required|numeric',
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

        return redirect()->to('administrator/objek_wisata')->with('success', 'Objek wisata berhasil diupdate');
    }

    public function destroy($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        
        // Soft delete by setting aktif to 0
        $objek->update(['aktif' => '0']);

        return redirect()->to('administrator/objek_wisata')->with('success', 'Objek wisata berhasil dihapus');
    }

    public function toggle_status($id)
    {
        $objek = ObjekWisata::findOrFail($id);
        $objek->update([
            'status' => $objek->status === 'aktif' ? 'nonaktif' : 'aktif'
        ]);

        return redirect()->back()->with('success', 'Status objek wisata berhasil diubah');
    }

    // Kelian Mobile Views
    public function index_kelian()
    {
        $objekWisata = ObjekWisata::orderBy('created_at', 'desc')->get();
        return view('backend.kelian.tiket_objek', compact('objekWisata'));
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
}
