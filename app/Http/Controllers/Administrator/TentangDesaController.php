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
        $request->validate(['konten_sejarah' => 'nullable|string']);
        $settings = $this->getSettings();
        $settings['sejarah_desa'] = $request->konten_sejarah ?? '';
        $this->saveSettings($settings);
        return redirect()->back()->with('success', 'Sejarah Desa Adat berhasil diperbarui!');
    }

    // Upload gambar untuk CKEditor (image upload callback)
    public function sejarahUploadMedia(Request $request)
    {
        $request->validate(['upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120']);
        $file = $request->file('upload');
        $fileName = 'sejarah_img_' . time() . '_' . str_shuffle('abcde') . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/tentang_desa/sejarah');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
        $file->move($dest, $fileName);
        return response()->json(['url' => asset('storage/tentang_desa/sejarah/' . $fileName)]);
    }

    // Upload video
    public function sejarahUploadVideo(Request $request)
    {
        $request->validate([
            'video'       => 'required|file|mimes:mp4,webm,ogg|max:102400',
            'judul_video' => 'nullable|string|max:255',
        ]);
        $file = $request->file('video');
        $fileName = 'sejarah_vid_' . time() . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/tentang_desa/sejarah');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
        $file->move($dest, $fileName);

        $settings = $this->getSettings();
        $videos = $settings['sejarah_videos'] ?? [];
        $videos[] = ['id' => uniqid(), 'judul' => $request->judul_video ?? '', 'file' => $fileName];
        $settings['sejarah_videos'] = $videos;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Video berhasil diunggah!');
    }

    // Update Bendesa + foto struktur desa
    public function sejarahUpdatePengurus(Request $request)
    {
        $request->validate([
            'nama_bendesa'    => 'nullable|string|max:255',
            'kata_sambutan'   => 'nullable|string',
            'no_telp_bendesa' => 'nullable|string|max:30',
            'foto_bendesa'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = $this->getSettings();
        $settings['bendesa_nama']          = $request->nama_bendesa ?? '';
        $settings['bendesa_sambutan']      = $request->kata_sambutan ?? '';
        $settings['bendesa_no_telp']       = $request->no_telp_bendesa ?? '';

        if ($request->hasFile('foto_bendesa')) {
            $file = $request->file('foto_bendesa');
            $fileName = 'bendesa_' . time() . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/tentang_desa/pengurus');
            if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
            $file->move($dest, $fileName);
            $settings['bendesa_foto'] = $fileName;
        }

        $this->saveSettings($settings);
        return redirect()->back()->with('success', 'Data Bendesa Adat berhasil diperbarui!');
    }

    public function sejarahUploadStruktur(Request $request)
    {
        $request->validate(['foto_struktur_desa' => 'required|image|mimes:jpeg,png,jpg|max:5120']);
        $file = $request->file('foto_struktur_desa');
        $fileName = 'struktur_desa_' . time() . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/tentang_desa/pengurus');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
        $file->move($dest, $fileName);

        $settings = $this->getSettings();
        $settings['foto_struktur_desa'] = $fileName;
        $this->saveSettings($settings);
        return redirect()->back()->with('success', 'Foto struktur Desa Adat berhasil diunggah!');
    }

    // =========================================================================
    // PRODUK HUKUM
    // =========================================================================

    public function produkHukumStore(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'file_produk' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->file('file_produk');
        $fileName = 'produk_hukum_' . time() . '_' . str_shuffle('abcde') . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/tentang_desa/produk_hukum');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);
        $file->move($dest, $fileName);

        $settings = $this->getSettings();
        $list = $settings['produk_hukum'] ?? [];
        $list[] = [
            'id'          => uniqid(),
            'nama_produk' => $request->nama_produk,
            'file'        => $fileName,
            'ext'         => $file->getClientOriginalExtension(),
            'created_at'  => now()->format('d M Y'),
        ];
        $settings['produk_hukum'] = $list;
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Produk hukum berhasil ditambahkan!');
    }

    public function produkHukumDelete(Request $request)
    {
        $request->validate(['id' => 'required|string']);
        $settings = $this->getSettings();
        $list = $settings['produk_hukum'] ?? [];
        $list = array_values(array_filter($list, fn($p) => $p['id'] !== $request->id));
        $settings['produk_hukum'] = $list;
        $this->saveSettings($settings);
        return redirect()->back()->with('success', 'Produk hukum berhasil dihapus!');
    }

    // =========================================================================
    // PENGURUS (legacy — redirect ke sejarah)
    // =========================================================================
    public function pengurus() { return redirect(url('administrator/tentang-desa/sejarah')); }
    public function pengurusStore(Request $request) { return redirect(url('administrator/tentang-desa/sejarah')); }
    public function pengurusDelete(Request $request) { return redirect(url('administrator/tentang-desa/sejarah')); }

    // =========================================================================
    // LEMBAGA
    // =========================================================================

    public function lembaga()
    {
        $settings = $this->getSettings();
        $lembagaList = $settings['lembaga_desa'] ?? [];
        return view('admin.pages.tentang_desa.lembaga', compact('lembagaList'));
    }

    public function lembagaCreate()
    {
        return view('admin.pages.tentang_desa.lembaga_create');
    }

    public function lembagaEdit(string $id)
    {
        $settings = $this->getSettings();
        $list = $settings['lembaga_desa'] ?? [];
        $lembaga = collect($list)->firstWhere('id', $id);
        if (!$lembaga) abort(404);
        return view('admin.pages.tentang_desa.lembaga_edit', compact('lembaga'));
    }

    public function lembagaUpdate(Request $request, string $id)
    {
        $request->validate([
            'nama_lembaga'          => 'required|string|max:255',
            'deskripsi'             => 'nullable|string',
            'ketua'                 => 'nullable|string|max:255',
            'logo'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_new.*'         => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'pengurus.*.nama'       => 'required|string|max:255',
            'pengurus.*.keterangan' => 'nullable|string|max:500',
            'pengurus.*.no_telp'    => 'nullable|string|max:30',
        ]);

        $settings = $this->getSettings();
        $list = $settings['lembaga_desa'] ?? [];
        $dest = public_path('storage/tentang_desa/lembaga');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);

        $list = array_map(function ($item) use ($id, $request, $dest) {
            if ($item['id'] !== $id) return $item;

            // Logo — keep existing if no new upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $item['logo'] = 'lembaga_' . time() . '_' . str_shuffle('abcde') . '.' . $file->getClientOriginalExtension();
                $file->move($dest, $item['logo']);
            }

            // Gallery — keep existing + append new
            $existing = $request->input('gallery_keep', []);
            $gallery = array_values(array_filter($existing));
            if ($request->hasFile('gallery_new')) {
                foreach ($request->file('gallery_new') as $i => $file) {
                    $gName = 'lembaga_gal_' . time() . '_' . $i . '_' . str_shuffle('abcde') . '.' . $file->getClientOriginalExtension();
                    $file->move($dest, $gName);
                    $gallery[] = $gName;
                }
            }
            $item['gallery'] = $gallery;

            // Pengurus — rebuild list
            $pengurusList = [];
            if ($request->has('pengurus')) {
                foreach ($request->pengurus as $i => $p) {
                    if (empty($p['nama'])) continue;
                    $fotoName = $p['foto_existing'] ?? null;
                    if (isset($request->file('pengurus')[$i]['foto']) && $request->file('pengurus')[$i]['foto']) {
                        $fotoFile = $request->file('pengurus')[$i]['foto'];
                        $fotoName = 'lembaga_pngg_' . time() . '_' . $i . '.' . $fotoFile->getClientOriginalExtension();
                        $fotoFile->move($dest, $fotoName);
                    }
                    $pengurusList[] = [
                        'id'         => $p['id'] ?? uniqid(),
                        'nama'       => $p['nama'],
                        'keterangan' => $p['keterangan'] ?? '',
                        'no_telp'    => $p['no_telp'] ?? '',
                        'foto'       => $fotoName,
                    ];
                }
            }

            $item['nama_lembaga'] = $request->nama_lembaga;
            $item['deskripsi']    = $request->deskripsi ?? '';
            $item['ketua']        = $request->ketua ?? '';
            $item['pengurus']     = $pengurusList;

            return $item;
        }, $list);

        $settings['lembaga_desa'] = $list;
        $this->saveSettings($settings);

        return redirect(url('administrator/tentang-desa/lembaga'))->with('success', 'Lembaga berhasil diperbarui!');
    }

    public function lembagaStore(Request $request)
    {
        $request->validate([
            'nama_lembaga'       => 'required|string|max:255',
            'deskripsi'          => 'nullable|string',
            'ketua'              => 'nullable|string|max:255',
            'logo'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery.*'          => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'pengurus.*.nama'    => 'required|string|max:255',
            'pengurus.*.keterangan' => 'nullable|string|max:500',
            'pengurus.*.no_telp' => 'nullable|string|max:30',
        ]);

        $settings = $this->getSettings();
        $list = $settings['lembaga_desa'] ?? [];
        $dest = public_path('storage/tentang_desa/lembaga');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);

        // Logo
        $logoName = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoName = 'lembaga_' . time() . '_' . str_shuffle('abcdefghij') . '.' . $file->getClientOriginalExtension();
            $file->move($dest, $logoName);
        }

        // Gallery
        $galleryFiles = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $gName = 'lembaga_gal_' . time() . '_' . $i . '_' . str_shuffle('abcde') . '.' . $file->getClientOriginalExtension();
                $file->move($dest, $gName);
                $galleryFiles[] = $gName;
            }
        }

        // Pengurus
        $pengurusList = [];
        if ($request->has('pengurus')) {
            foreach ($request->pengurus as $i => $p) {
                if (empty($p['nama'])) continue;
                $fotoName = null;
                if (isset($request->file('pengurus')[$i]['foto']) && $request->file('pengurus')[$i]['foto']) {
                    $fotoFile = $request->file('pengurus')[$i]['foto'];
                    $fotoName = 'lembaga_pngg_' . time() . '_' . $i . '.' . $fotoFile->getClientOriginalExtension();
                    $fotoFile->move($dest, $fotoName);
                }
                $pengurusList[] = [
                    'id'          => uniqid(),
                    'nama'        => $p['nama'],
                    'keterangan'  => $p['keterangan'] ?? '',
                    'no_telp'     => $p['no_telp'] ?? '',
                    'foto'        => $fotoName,
                ];
            }
        }

        $list[] = [
            'id'           => uniqid(),
            'nama_lembaga' => $request->nama_lembaga,
            'deskripsi'    => $request->deskripsi ?? '',
            'ketua'        => $request->ketua ?? '',
            'logo'         => $logoName,
            'gallery'      => $galleryFiles,
            'pengurus'     => $pengurusList,
        ];

        $settings['lembaga_desa'] = $list;
        $this->saveSettings($settings);

        return redirect(url('administrator/tentang-desa/lembaga'))->with('success', 'Lembaga berhasil ditambahkan!');
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

    public function bupdaTimUpdate(Request $request)
    {
        $request->validate([
            'id'      => 'required|string',
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $dest = public_path('storage/tentang_desa/bupda');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);

        $bupda['tim'] = array_map(function ($t) use ($request, $dest) {
            if ($t['id'] !== $request->id) return $t;
            $t['nama']    = $request->nama;
            $t['jabatan'] = $request->jabatan;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $t['foto'] = 'bupda_tim_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($dest, $t['foto']);
            }
            return $t;
        }, $bupda['tim'] ?? []);

        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);
        return redirect()->back()->with('success', 'Anggota tim berhasil diperbarui!');
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

    public function bupdaProgramUpdate(Request $request)
    {
        $request->validate([
            'id'           => 'required|string',
            'nama_program' => 'required|string|max:255',
            'keterangan'   => 'nullable|string',
            'lokasi'       => 'nullable|string|max:255',
            'no_kontak'    => 'nullable|string|max:30',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $settings = $this->getSettings();
        $bupda = $settings['bupda_desa'] ?? [];
        $dest = public_path('storage/tentang_desa/bupda');
        if (!File::isDirectory($dest)) File::makeDirectory($dest, 0777, true, true);

        $bupda['program'] = array_map(function ($p) use ($request, $dest) {
            if ($p['id'] !== $request->id) return $p;
            $p['nama_program'] = $request->nama_program;
            $p['keterangan']   = $request->keterangan ?? '';
            $p['lokasi']       = $request->lokasi ?? '';
            $p['no_kontak']    = $request->no_kontak ?? '';
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $p['foto'] = 'bupda_prog_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($dest, $p['foto']);
            }
            return $p;
        }, $bupda['program'] ?? []);

        $settings['bupda_desa'] = $bupda;
        $this->saveSettings($settings);
        return redirect()->back()->with('success', 'Program berhasil diperbarui!');
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
