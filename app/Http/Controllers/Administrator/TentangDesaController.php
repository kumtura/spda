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
    // BUMDES
    // =========================================================================

    public function bumdes()
    {
        $settings = $this->getSettings();
        $bumdesList = $settings['bumdes_desa'] ?? [];
        return view('admin.pages.tentang_desa.bumdes', compact('bumdesList'));
    }

    public function bumdesStore(Request $request)
    {
        $request->validate([
            'nama_bumdes'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'ketua'        => 'nullable|string|max:255',
            'tahun_berdiri'=> 'nullable|string|max:10',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = $this->getSettings();
        $list = $settings['bumdes_desa'] ?? [];

        $logoName = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoName = 'bumdes_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/tentang_desa/bumdes');
            if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
            $file->move($dest, $logoName);
        }

        $list[] = [
            'id'           => uniqid(),
            'nama_bumdes'  => $request->nama_bumdes,
            'deskripsi'    => $request->deskripsi ?? '',
            'ketua'        => $request->ketua ?? '',
            'tahun_berdiri'=> $request->tahun_berdiri ?? '',
            'logo'         => $logoName,
        ];

        $settings['bumdes_desa'] = $list;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Badan Usaha berhasil ditambahkan!');
    }

    public function bumdesDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $settings = $this->getSettings();
        $list = $settings['bumdes_desa'] ?? [];

        $list = array_filter($list, fn($item) => $item['id'] !== $request->id);

        $settings['bumdes_desa'] = array_values($list);
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Badan Usaha berhasil dihapus!');
    }
}
