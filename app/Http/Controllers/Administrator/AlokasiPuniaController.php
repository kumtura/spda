<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\AlokasiPunia;
use App\Models\KategoriPunia;
use Illuminate\Http\Request;

class AlokasiPuniaController extends Controller
{
    public function index()
    {
        $alokasi = AlokasiPunia::with('kategori')
                        ->where('aktif', '1')
                        ->orderBy('tanggal_alokasi', 'desc')
                        ->get();
                        
        $kategori = KategoriPunia::where('aktif', '1')
                        ->orderBy('nama_kategori', 'asc')
                        ->get();
                        
        return view('admin.pages.alokasi_punia.table', compact('alokasi', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori_punia' => 'required|exists:tb_kategori_punia,id_kategori_punia',
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_alokasi' => 'required|date',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/alokasi_punia'), $filename);
                $fotoPaths[] = 'storage/alokasi_punia/' . $filename;
            }
        }

        AlokasiPunia::create([
            'id_kategori_punia' => $request->id_kategori_punia,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
            'tanggal_alokasi' => $request->tanggal_alokasi,
            'foto' => !empty($fotoPaths) ? $fotoPaths : null,
            'aktif' => '1'
        ]);

        return redirect()->back()->with('success', 'Alokasi Punia berhasil dicatat!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_alokasi_punia' => 'required|integer',
            'id_kategori_punia' => 'required|exists:tb_kategori_punia,id_kategori_punia',
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_alokasi' => 'required|date',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $alokasi = AlokasiPunia::findOrFail($request->id_alokasi_punia);
        
        $fotoPaths = $alokasi->foto ?? [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/alokasi_punia'), $filename);
                $fotoPaths[] = 'storage/alokasi_punia/' . $filename;
            }
        }

        $alokasi->update([
            'id_kategori_punia' => $request->id_kategori_punia,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
            'tanggal_alokasi' => $request->tanggal_alokasi,
            'foto' => !empty($fotoPaths) ? $fotoPaths : null
        ]);

        return redirect()->back()->with('success', 'Alokasi Punia berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $alokasi = AlokasiPunia::findOrFail($id);
        $alokasi->update(['aktif' => '0']);
        return redirect()->back()->with('success', 'Alokasi Punia berhasil dihapus!');
    }
}
