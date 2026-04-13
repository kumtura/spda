<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class TentangDesaController extends Controller
{
    private function getSettings(): array
    {
        $path = storage_path('app/settings.json');
        if (File::exists($path)) {
            return json_decode(File::get($path), true) ?? [];
        }
        return [];
    }

    private function saveSettings(array $settings): void
    {
        File::put(storage_path('app/settings.json'), json_encode($settings, JSON_PRETTY_PRINT));
    }

    // =========================================================================
    // SEJARAH
    // =========================================================================

    public function sejarah()
    {
        $settings = $this->getSettings();
        return view('admin.pages.tentang_desa.sejarah', compact('settings'));
    }

    public function sejarahUpdate(Request $request)
    {
        $request->validate([
            'konten_sejarah' => 'required|string',
        ]);

        $settings = $this->getSettings();
        $settings['sejarah_desa'] = $request->konten_sejarah;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Sejarah Desa Adat berhasil diperbarui!');
    }

    // =========================================================================
    // PENGURUS
    // =========================================================================

    public function pengurus()
    {
        $settings = $this->getSettings();
        $pengurusList = $settings['pengurus_desa'] ?? [];
        return view('admin.pages.tentang_desa.pengurus', compact('pengurusList'));
    }

    public function pengurusStore(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'jabatan'  => 'required|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = $this->getSettings();
        $list = $settings['pengurus_desa'] ?? [];

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = 'pengurus_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/tentang_desa/pengurus');
            if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
            $file->move($dest, $fotoName);
        }

        $list[] = [
            'id'      => uniqid(),
            'nama'    => $request->nama,
            'jabatan' => $request->jabatan,
            'no_hp'   => $request->no_hp ?? '',
            'foto'    => $fotoName,
        ];

        $settings['pengurus_desa'] = $list;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Pengurus berhasil ditambahkan!');
    }

    public function pengurusDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $settings = $this->getSettings();
        $list = $settings['pengurus_desa'] ?? [];

        $list = array_filter($list, fn($item) => $item['id'] !== $request->id);

        $settings['pengurus_desa'] = array_values($list);
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Pengurus berhasil dihapus!');
    }

    // =========================================================================
    // LEMBAGA
    // =========================================================================

    public function lembaga()
    {
        $settings = $this->getSettings();
        $lembagaList = $settings['lembaga_desa'] ?? [];
        return view('admin.pages.tentang_desa.lembaga', compact('lembagaList'));
    }

    public function lembagaStore(Request $request)
    {
        $request->validate([
            'nama_lembaga' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'ketua'        => 'nullable|string|max:255',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = $this->getSettings();
        $list = $settings['lembaga_desa'] ?? [];

        $logoName = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoName = 'lembaga_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/tentang_desa/lembaga');
            if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
            $file->move($dest, $logoName);
        }

        $list[] = [
            'id'           => uniqid(),
            'nama_lembaga' => $request->nama_lembaga,
            'deskripsi'    => $request->deskripsi ?? '',
            'ketua'        => $request->ketua ?? '',
            'logo'         => $logoName,
        ];

        $settings['lembaga_desa'] = $list;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Lembaga berhasil ditambahkan!');
    }

    public function lembagaDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $settings = $this->getSettings();
        $list = $settings['lembaga_desa'] ?? [];

        $list = array_filter($list, fn($item) => $item['id'] !== $request->id);

        $settings['lembaga_desa'] = array_values($list);
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Lembaga berhasil dihapus!');
    }

    // =========================================================================
    // BUPDA (Badan Usaha Padruwen Desa Adat)
    // =========================================================================

    public function bupda()
    {
        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [
            'nama'        => '',
            'deskripsi'   => '',
            'tahun_berdiri' => '',
            'foto_struktur' => null,
            'tim'         => [],
            'program'     => [],
            'dokumentasi' => [],
        ];
        return view('admin.pages.tentang_desa.bupda', compact('bupda'));
    }

    public function bupdaUpdateInfo(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'tahun_berdiri' => 'nullable|string|max:10',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $bupda['nama']          = $request->nama;
        $bupda['deskripsi']     = $request->deskripsi ?? '';
        $bupda['tahun_berdiri'] = $request->tahun_berdiri ?? '';
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Informasi BUPDA berhasil diperbarui!');
    }

    public function bupdaUploadStruktur(Request $request)
    {
        $request->validate([
            'foto_struktur' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];

        $file = $request->file('foto_struktur');
        $fileName = 'bupda_struktur_' . time() . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/tentang_desa/bupda');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
        $file->move($dest, $fileName);

        $bupda['foto_struktur'] = $fileName;
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Foto struktur BUPDA berhasil diunggah!');
    }

    public function bupdaTimStore(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $tim = $bupda['tim'] ?? [];

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = 'bupda_tim_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/tentang_desa/bupda');
            if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
            $file->move($dest, $fotoName);
        }

        $tim[] = [
            'id'      => uniqid(),
            'nama'    => $request->nama,
            'jabatan' => $request->jabatan,
            'foto'    => $fotoName,
        ];

        $bupda['tim'] = $tim;
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Anggota tim BUPDA berhasil ditambahkan!');
    }

    public function bupdaTimDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $tim = $bupda['tim'] ?? [];
        $bupda['tim'] = array_values(array_filter($tim, fn($t) => $t['id'] !== $request->id));
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Anggota tim berhasil dihapus!');
    }

    public function bupdaProgramStore(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'keterangan'   => 'nullable|string',
            'lokasi'       => 'nullable|string|max:255',
            'no_kontak'    => 'nullable|string|max:30',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $program = $bupda['program'] ?? [];

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = 'bupda_prog_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/tentang_desa/bupda');
            if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
            $file->move($dest, $fotoName);
        }

        $program[] = [
            'id'           => uniqid(),
            'nama_program' => $request->nama_program,
            'keterangan'   => $request->keterangan ?? '',
            'lokasi'       => $request->lokasi ?? '',
            'no_kontak'    => $request->no_kontak ?? '',
            'foto'         => $fotoName,
        ];

        $bupda['program'] = $program;
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Program BUPDA berhasil ditambahkan!');
    }

    public function bupdaProgramDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $program = $bupda['program'] ?? [];
        $bupda['program'] = array_values(array_filter($program, fn($p) => $p['id'] !== $request->id));
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Program berhasil dihapus!');
    }

    public function bupdaDokumentasiStore(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'foto'   => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $docs = $bupda['dokumentasi'] ?? [];

        $file = $request->file('foto');
        $fotoName = 'bupda_dok_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/tentang_desa/bupda');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
        $file->move($dest, $fotoName);

        $docs[] = [
            'id'    => uniqid(),
            'judul' => $request->judul,
            'foto'  => $fotoName,
        ];

        $bupda['dokumentasi'] = $docs;
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    public function bupdaDokumentasiDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $docs = $bupda['dokumentasi'] ?? [];
        $bupda['dokumentasi'] = array_values(array_filter($docs, fn($d) => $d['id'] !== $request->id));
        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Dokumentasi berhasil dihapus!');
    }

    // Legacy methods (kept for backward compat)
    public function bumdes() { return redirect(url('administrator/tentang-desa/bupda')); }
    public function bumdesStore(Request $request) { return redirect(url('administrator/tentang-desa/bupda')); }
    public function bumdesDelete(Request $request) { return redirect(url('administrator/tentang-desa/bupda')); }
}
