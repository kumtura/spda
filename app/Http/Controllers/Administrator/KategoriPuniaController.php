<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\KategoriPunia;
use Illuminate\Http\Request;

class KategoriPuniaController extends Controller
{
    public function index()
    {
        $kategori = KategoriPunia::where('aktif', '1')
                        ->orderBy('id_kategori_punia', 'desc')
                        ->get();
        return view('admin.pages.kategori_punia.table', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'ikon' => 'nullable|string|max:50',
            'deskripsi_singkat' => 'nullable|string'
        ]);

        KategoriPunia::create([
            'nama_kategori' => $request->nama_kategori,
            'ikon' => $request->ikon ?? 'bi-wallet2',
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'aktif' => '1'
        ]);

        return redirect()->back()->with('success', 'Kategori Punia berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_kategori_punia' => 'required|integer',
            'nama_kategori' => 'required|string|max:100',
            'ikon' => 'nullable|string|max:50',
            'deskripsi_singkat' => 'nullable|string'
        ]);

        $kategori = KategoriPunia::findOrFail($request->id_kategori_punia);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'ikon' => $request->ikon ?? 'bi-wallet2',
            'deskripsi_singkat' => $request->deskripsi_singkat
        ]);

        return redirect()->back()->with('success', 'Kategori Punia berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriPunia::findOrFail($id);
        $kategori->update(['aktif' => '0']);
        return redirect()->back()->with('success', 'Kategori Punia berhasil dihapus!');
    }
}
