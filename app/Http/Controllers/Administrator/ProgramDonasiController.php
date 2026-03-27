<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\ProgramDonasi;
use App\Models\KategoriDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProgramDonasiController extends Controller
{
    public function index()
    {
        $program = ProgramDonasi::with('kategori')
                        ->where('aktif', '1')
                        ->orderBy('id_program_donasi', 'desc')
                        ->get();
                        
        $kategori = KategoriDonasi::where('aktif', '1')
                        ->orderBy('nama_kategori', 'asc')
                        ->get();
                        
        return view('admin.pages.program_donasi.table', compact('program', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori_donasi' => 'required|exists:tb_kategori_donasi,id_kategori_donasi',
            'nama_program' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'target_dana' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date'
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            try {
                $foto = $request->file('foto');
                $fotoName = 'program_' . time() . '.' . $foto->getClientOriginalExtension();
                
                // Use storage path directly (symlink target)
                $destPath = storage_path('app/public/program_donasi');
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
                
                $foto->move($destPath, $fotoName);
                \Log::info('Program Donasi foto uploaded: ' . $fotoName . ' to ' . $destPath);
            } catch (\Exception $e) {
                \Log::error('Program Donasi foto upload failed: ' . $e->getMessage());
                $fotoName = null;
            }
        }

        ProgramDonasi::create([
            'id_kategori_donasi' => $request->id_kategori_donasi,
            'nama_program' => $request->nama_program,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoName,
            'target_dana' => $request->target_dana,
            'tanggal_mulai' => $request->tanggal_mulai,
            'aktif' => '1'
        ]);

        return redirect()->back()->with('success', 'Program Donasi berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        \Log::info('Program Donasi update called', [
            'id' => $request->id_program_donasi,
            'has_foto' => $request->hasFile('foto'),
            'foto_valid' => $request->hasFile('foto') ? $request->file('foto')->isValid() : 'N/A',
            'all_fields' => array_keys($request->all())
        ]);

        $request->validate([
            'id_program_donasi' => 'required|integer',
            'id_kategori_donasi' => 'required|exists:tb_kategori_donasi,id_kategori_donasi',
            'nama_program' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
            'target_dana' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date'
        ]);

        $program = ProgramDonasi::findOrFail($request->id_program_donasi);

        $data = [
            'id_kategori_donasi' => $request->id_kategori_donasi,
            'nama_program' => $request->nama_program,
            'deskripsi' => $request->deskripsi,
            'target_dana' => $request->target_dana,
            'tanggal_mulai' => $request->tanggal_mulai
        ];

        if ($request->hasFile('foto')) {
            try {
                // Use storage path directly (symlink target)
                $destPath = storage_path('app/public/program_donasi');
                
                // Delete old photo if exists
                if ($program->foto && file_exists($destPath . '/' . $program->foto)) {
                    @unlink($destPath . '/' . $program->foto);
                }
                
                $foto = $request->file('foto');
                $fotoName = 'program_' . time() . '.' . $foto->getClientOriginalExtension();
                
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
                
                $foto->move($destPath, $fotoName);
                $data['foto'] = $fotoName;
                \Log::info('Program Donasi foto updated: ' . $fotoName . ' to ' . $destPath);
            } catch (\Exception $e) {
                \Log::error('Program Donasi foto update failed: ' . $e->getMessage());
            }
        }

        $program->update($data);

        return redirect()->back()->with('success', 'Program Donasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $program = ProgramDonasi::findOrFail($id);
        $program->update(['aktif' => '0']);
        return redirect()->back()->with('success', 'Program Donasi berhasil dihapus!');
    }
}
