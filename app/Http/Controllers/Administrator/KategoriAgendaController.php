<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\KategoriAgenda;
use Illuminate\Http\Request;

class KategoriAgendaController extends Controller
{
    public function index()
    {
        $kategori = KategoriAgenda::where('aktif', '1')->orderBy('nama_kategori', 'asc')->get();
        return view('admin.pages.agenda.kategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        KategoriAgenda::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
            'aktif' => '1'
        ]);

        return redirect()->back()->with('success', 'Kategori agenda berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_kategori_agenda' => 'required|exists:tb_kategori_agenda,id_kategori_agenda',
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $kategori = KategoriAgenda::find($request->id_kategori_agenda);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Kategori agenda berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kategori = KategoriAgenda::find($id);
        if ($kategori) {
            $kategori->update(['aktif' => '0']);
            return redirect()->back()->with('success', 'Kategori agenda berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Kategori tidak ditemukan');
    }
}
