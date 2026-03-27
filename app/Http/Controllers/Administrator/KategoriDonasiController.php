<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\KategoriDonasi;
use Illuminate\Http\Request;

class KategoriDonasiController extends Controller
{
    public function index()
    {
        $kategori = KategoriDonasi::where('aktif', '1')
                        ->orderBy('id_kategori_donasi', 'desc')
                        ->get();
        return view('admin.pages.kategori_donasi.table', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi_singkat' => 'nullable|string'
        ]);

        KategoriDonasi::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'aktif' => '1'
        ]);

        return redirect()->back()->with('success', 'Kategori Program Donasi berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_kategori_donasi' => 'required|integer',
            'nama_kategori' => 'required|string|max:100',
            'deskripsi_singkat' => 'nullable|string'
        ]);

        $kategori = KategoriDonasi::findOrFail($request->id_kategori_donasi);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi_singkat' => $request->deskripsi_singkat
        ]);

        return redirect()->back()->with('success', 'Kategori Program Donasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriDonasi::findOrFail($id);
        $kategori->update(['aktif' => '0']);
        return redirect()->back()->with('success', 'Kategori Program Donasi berhasil dihapus!');
    }
}
