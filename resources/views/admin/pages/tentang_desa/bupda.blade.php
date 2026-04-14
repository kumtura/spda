@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{
    tabActive: 'info',
    showTimModal: false,
    showProgramModal: false,
    showDokModal: false,
    showTimEditModal: false,
    showProgramEditModal: false,
    editTim: { id: '', nama: '', jabatan: '' },
    editProgram: { id: '', nama_program: '', keterangan: '', lokasi: '', no_kontak: '' },
    openEditTim(anggota) {
        this.editTim = { id: anggota.id, nama: anggota.nama, jabatan: anggota.jabatan };
        this.showTimEditModal = true;
    },
    openEditProgram(prog) {
        this.editProgram = { id: prog.id, nama_program: prog.nama_program, keterangan: prog.keterangan, lokasi: prog.lokasi, no_kontak: prog.no_kontak };
        this.showProgramEditModal = true;
    }
}">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1">
                <i class="bi bi-arrow-left mr-1"></i>
                <a href="{{ url('administrator/') }}">Dashboard</a> / Tentang Desa
            </p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">BUPDA Desa Adat</h1>
            <p class="text-slate-500 font-medium text-sm">Badan Usaha Padruwen Desa Adat — kelola informasi, tim, program, dan dokumentasi.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- TAB NAV --}}
    <div class="flex gap-1 bg-slate-100 rounded-2xl p-1 w-full overflow-x-auto">
        @foreach([
            ['key'=>'info',      'label'=>'Informasi',    'icon'=>'bi-info-circle'],
            ['key'=>'struktur',  'label'=>'Struktur',     'icon'=>'bi-diagram-3'],
            ['key'=>'tim',       'label'=>'Tim',          'icon'=>'bi-people'],
            ['key'=>'program',   'label'=>'Program',      'icon'=>'bi-grid-1x2'],
            ['key'=>'dokumentasi','label'=>'Dokumentasi', 'icon'=>'bi-images'],
        ] as $t)
        <button @click="tabActive = '{{ $t['key'] }}'"
                :class="tabActive === '{{ $t['key'] }}' ? 'bg-white text-primary-light shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                class="flex-1 min-w-[90px] flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-bold transition-all whitespace-nowrap">
            <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: INFORMASI
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tabActive === 'info'" x-transition>
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-black text-slate-700 mb-5 flex items-center gap-2">
                <i class="bi bi-info-circle text-primary-light"></i> Informasi Dasar BUPDA
            </h3>
            <form action="{{ url('administrator/tentang-desa/bupda/update-info') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama BUPDA <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ $bupda['nama'] ?? '' }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                        placeholder="Contoh: BUPDA Kumtura Mandiri">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tahun Berdiri</label>
                    <input type="text" name="tahun_berdiri" value="{{ $bupda['tahun_berdiri'] ?? '' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                        placeholder="2018">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi / Visi Misi</label>
                    <textarea name="deskripsi" rows="5"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-y"
                        placeholder="Tulis deskripsi, visi, dan misi BUPDA...">{{ $bupda['deskripsi'] ?? '' }}</textarea>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                        <i class="bi bi-save mr-2"></i>Simpan Informasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: STRUKTUR ORGANISASI
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tabActive === 'struktur'" x-transition>
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2">
                <i class="bi bi-diagram-3 text-primary-light"></i> Foto Struktur Organisasi
            </h3>

            @if(!empty($bupda['foto_struktur']))
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                <img src="{{ asset('storage/tentang_desa/bupda/' . $bupda['foto_struktur']) }}"
                     class="w-full object-contain max-h-[500px]" alt="Struktur BUPDA">
            </div>
            @else
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-10 text-center">
                <i class="bi bi-diagram-3 text-4xl text-slate-300 block mb-2"></i>
                <p class="text-sm font-bold text-slate-400">Belum ada foto struktur organisasi.</p>
            </div>
            @endif

            <form action="{{ url('administrator/tentang-desa/bupda/upload-struktur') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                    {{ !empty($bupda['foto_struktur']) ? 'Ganti Foto Struktur' : 'Upload Foto Struktur' }}
                </label>
                <input type="file" name="foto_struktur" required accept="image/png,image/jpeg,image/jpg"
                    class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                <p class="text-[10px] text-slate-400">Format: JPG, PNG. Maks 5MB. Gunakan gambar landscape untuk hasil terbaik.</p>
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all">
                        <i class="bi bi-cloud-arrow-up mr-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: TIM BUPDA
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tabActive === 'tim'" x-transition>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2">
                <i class="bi bi-people text-primary-light"></i> Tim BUPDA
            </h3>
            <button @click="showTimModal = true"
                    class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-5 py-2 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                <i class="bi bi-person-plus-fill"></i> Tambah Anggota
            </button>
        </div>

        @php $tim = $bupda['tim'] ?? []; @endphp
        @if(count($tim) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($tim as $anggota)
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm text-center">
                <div class="h-20 w-20 rounded-2xl bg-slate-100 overflow-hidden mx-auto mb-3 flex items-center justify-center border border-slate-200">
                    @if(!empty($anggota['foto']))
                        <img src="{{ asset('storage/tentang_desa/bupda/' . $anggota['foto']) }}" class="h-full w-full object-cover" alt="{{ $anggota['nama'] }}">
                    @else
                        <i class="bi bi-person-fill text-3xl text-slate-300"></i>
                    @endif
                </div>
                <p class="text-sm font-black text-slate-800">{{ $anggota['nama'] }}</p>
                <span class="inline-block mt-1 bg-primary-light/10 text-primary-light text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">{{ $anggota['jabatan'] }}</span>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-center gap-4">
                    <button type="button"
                            @click="openEditTim({ id: '{{ $anggota['id'] }}', nama: '{{ addslashes($anggota['nama']) }}', jabatan: '{{ addslashes($anggota['jabatan']) }}' })"
                            class="text-xs font-bold text-primary-light hover:text-primary-dark transition-colors">
                        <i class="bi bi-pencil-square mr-1"></i>Edit
                    </button>
                    <form action="{{ url('administrator/tentang-desa/bupda/tim/delete') }}" method="POST" onsubmit="return confirm('Hapus anggota ini?')">
                        @csrf
                        <input type="hidden" name="id" value="{{ $anggota['id'] }}">
                        <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors">
                            <i class="bi bi-trash3 mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <i class="bi bi-people text-4xl text-slate-300 block mb-3"></i>
            <p class="text-sm font-bold text-slate-400">Belum ada anggota tim. Klik "Tambah Anggota" untuk memulai.</p>
        </div>
        @endif

        {{-- Modal Tambah Tim --}}
        <template x-teleport="body">
            <div x-show="showTimModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4" x-transition x-cloak>
                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200" @click.away="showTimModal = false">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-black text-slate-800">Tambah Anggota Tim</h3>
                        <button @click="showTimModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <form action="{{ url('administrator/tentang-desa/bupda/tim/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jabatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="jabatan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Foto (Opsional)</label>
                            <input type="file" name="foto" accept="image/png,image/jpeg,image/jpg"
                                class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showTimModal = false" class="px-5 py-2.5 text-slate-400 font-bold text-sm">Batal</button>
                            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: PROGRAM
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tabActive === 'program'" x-transition>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2">
                <i class="bi bi-grid-1x2 text-primary-light"></i> Program BUPDA
            </h3>
            <button @click="showProgramModal = true"
                    class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-5 py-2 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                <i class="bi bi-plus-lg"></i> Tambah Program
            </button>
        </div>

        @php $program = $bupda['program'] ?? []; @endphp
        @if(count($program) > 0)
        <div class="space-y-4">
            @foreach($program as $prog)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                @if(!empty($prog['foto']))
                <div class="h-40 bg-slate-100 overflow-hidden">
                    <img src="{{ asset('storage/tentang_desa/bupda/' . $prog['foto']) }}" class="w-full h-full object-cover" alt="{{ $prog['nama_program'] }}">
                </div>
                @endif
                <div class="p-5">
                    <h4 class="text-sm font-black text-slate-800 mb-2">{{ $prog['nama_program'] }}</h4>
                    @if(!empty($prog['keterangan']))
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">{{ $prog['keterangan'] }}</p>
                    @endif
                    <div class="flex flex-wrap gap-3 text-[10px] text-slate-400">
                        @if(!empty($prog['lokasi']))
                        <span class="flex items-center gap-1"><i class="bi bi-geo-alt text-primary-light"></i>{{ $prog['lokasi'] }}</span>
                        @endif
                        @if(!empty($prog['no_kontak']))
                        <span class="flex items-center gap-1"><i class="bi bi-telephone text-primary-light"></i>{{ $prog['no_kontak'] }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                        <button type="button"
                                @click="openEditProgram({ id: '{{ $prog['id'] }}', nama_program: '{{ addslashes($prog['nama_program']) }}', keterangan: '{{ addslashes($prog['keterangan'] ?? '') }}', lokasi: '{{ addslashes($prog['lokasi'] ?? '') }}', no_kontak: '{{ addslashes($prog['no_kontak'] ?? '') }}' })"
                                class="text-xs font-bold text-primary-light hover:text-primary-dark transition-colors">
                            <i class="bi bi-pencil-square mr-1"></i>Edit
                        </button>
                        <form action="{{ url('administrator/tentang-desa/bupda/program/delete') }}" method="POST" onsubmit="return confirm('Hapus program ini?')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $prog['id'] }}">
                            <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors">
                                <i class="bi bi-trash3 mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <i class="bi bi-grid-1x2 text-4xl text-slate-300 block mb-3"></i>
            <p class="text-sm font-bold text-slate-400">Belum ada program. Klik "Tambah Program" untuk memulai.</p>
        </div>
        @endif

        {{-- Modal Tambah Program --}}
        <template x-teleport="body">
            <div x-show="showProgramModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8 overflow-y-auto" x-transition x-cloak>
                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 my-auto" @click.away="showProgramModal = false">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-black text-slate-800">Tambah Program</h3>
                        <button @click="showProgramModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <form action="{{ url('administrator/tentang-desa/bupda/program/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Program <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_program" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Lokasi (Opsional)</label>
                                <input type="text" name="lokasi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. Kontak (Opsional)</label>
                                <input type="text" name="no_kontak" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Foto Program (Opsional)</label>
                            <input type="file" name="foto" accept="image/png,image/jpeg,image/jpg"
                                class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showProgramModal = false" class="px-5 py-2.5 text-slate-400 font-bold text-sm">Batal</button>
                            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: DOKUMENTASI KEGIATAN
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tabActive === 'dokumentasi'" x-transition>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2">
                <i class="bi bi-images text-primary-light"></i> Dokumentasi Kegiatan
            </h3>
            <button @click="showDokModal = true"
                    class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-5 py-2 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                <i class="bi bi-image-fill"></i> Tambah Foto
            </button>
        </div>

        @php $dokumentasi = $bupda['dokumentasi'] ?? []; @endphp
        @if(count($dokumentasi) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($dokumentasi as $dok)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm group">
                <div class="h-40 bg-slate-100 overflow-hidden">
                    <img src="{{ asset('storage/tentang_desa/bupda/' . $dok['foto']) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $dok['judul'] }}">
                </div>
                <div class="p-3">
                    <p class="text-xs font-bold text-slate-700 leading-tight line-clamp-2">{{ $dok['judul'] }}</p>
                    <div class="flex justify-end mt-2">
                        <form action="{{ url('administrator/tentang-desa/bupda/dokumentasi/delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $dok['id'] }}">
                            <button type="submit" class="text-[10px] font-bold text-rose-400 hover:text-rose-600 transition-colors">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <i class="bi bi-images text-4xl text-slate-300 block mb-3"></i>
            <p class="text-sm font-bold text-slate-400">Belum ada dokumentasi. Klik "Tambah Foto" untuk memulai.</p>
        </div>
        @endif

        {{-- Modal Tambah Dokumentasi --}}
        <template x-teleport="body">
            <div x-show="showDokModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4" x-transition x-cloak>
                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200" @click.away="showDokModal = false">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-black text-slate-800">Tambah Dokumentasi</h3>
                        <button @click="showDokModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <form action="{{ url('administrator/tentang-desa/bupda/dokumentasi/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Judul / Keterangan Foto <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10" placeholder="Contoh: Rapat Koordinasi 2024">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Foto <span class="text-rose-500">*</span></label>
                            <input type="file" name="foto" required accept="image/png,image/jpeg,image/jpg"
                                class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 5MB.</p>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showDokModal = false" class="px-5 py-2.5 text-slate-400 font-bold text-sm">Batal</button>
                            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal Edit Tim --}}
    <template x-teleport="body">
        <div x-show="showTimEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4" x-transition x-cloak>
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200" @click.away="showTimEditModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800">Edit Anggota Tim</h3>
                    <button @click="showTimEditModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ url('administrator/tentang-desa/bupda/tim/update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="id" x-model="editTim.id">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" x-model="editTim.nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="jabatan" x-model="editTim.jabatan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto" accept="image/png,image/jpeg,image/jpg"
                            class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showTimEditModal = false" class="px-5 py-2.5 text-slate-400 font-bold text-sm">Batal</button>
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- Modal Edit Program --}}
    <template x-teleport="body">
        <div x-show="showProgramEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8 overflow-y-auto" x-transition x-cloak>
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 my-auto" @click.away="showProgramEditModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800">Edit Program</h3>
                    <button @click="showProgramEditModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ url('administrator/tentang-desa/bupda/program/update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="id" x-model="editProgram.id">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Program <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_program" x-model="editProgram.nama_program" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                        <textarea name="keterangan" x-model="editProgram.keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Lokasi</label>
                            <input type="text" name="lokasi" x-model="editProgram.lokasi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. Kontak</label>
                            <input type="text" name="no_kontak" x-model="editProgram.no_kontak" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto" accept="image/png,image/jpeg,image/jpg"
                            class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showProgramEditModal = false" class="px-5 py-2.5 text-slate-400 font-bold text-sm">Batal</button>
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
