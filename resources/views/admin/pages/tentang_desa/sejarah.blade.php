@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ tab: 'sejarah' }">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1">
                <i class="bi bi-arrow-left mr-1"></i>
                <a href="{{ url('administrator/') }}">Dashboard</a> / Tentang Desa Adat
            </p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Sejarah & Pengurus Desa Adat</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola konten sejarah, pengurus, dan produk hukum Desa Adat.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- TAB NAV --}}
    <div class="flex gap-1 bg-slate-100 rounded-2xl p-1">
        @foreach([
            ['key'=>'sejarah',  'label'=>'Sejarah Desa Adat',    'icon'=>'bi-book-half'],
            ['key'=>'pengurus', 'label'=>'Pengurus Desa Adat',   'icon'=>'bi-person-badge'],
            ['key'=>'gallery',  'label'=>'Header Gallery',       'icon'=>'bi-images'],
            ['key'=>'hukum',    'label'=>'Produk Hukum',    'icon'=>'bi-file-earmark-text'],
        ] as $t)
        <button @click="tab = '{{ $t['key'] }}'"
                :class="tab === '{{ $t['key'] }}' ? 'bg-white text-primary-light shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-bold transition-all">
            <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: SEJARAH
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'sejarah'" x-transition>
        <div class="space-y-5">
            {{-- Rich Text Editor --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
                    <i class="bi bi-book-half text-primary-light"></i> Konten Sejarah Desa Adat
                </h3>
                <form action="{{ url('administrator/tentang-desa/sejarah/update') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <textarea id="sejarah_editor" name="konten_sejarah" rows="20"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none">{{ $settings['sejarah_desa'] ?? '' }}</textarea>
                        <p class="text-[10px] text-slate-400">Konten ini ditampilkan di halaman publik Tentang Desa Adat.</p>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                                <i class="bi bi-save mr-2"></i>Simpan Sejarah
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Upload Video --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
                    <i class="bi bi-camera-video text-primary-light"></i> Upload Video Sejarah
                </h3>

                @php $videos = $settings['sejarah_videos'] ?? []; @endphp
                @if(count($videos) > 0)
                <div class="space-y-3 mb-5">
                    @foreach($videos as $vid)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="h-10 w-10 bg-primary-light/10 rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi bi-play-circle text-primary-light text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate">{{ $vid['judul'] ?: $vid['file'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $vid['file'] }}</p>
                        </div>
                        <video src="{{ asset('storage/tentang_desa/sejarah/' . $vid['file']) }}" controls class="h-16 w-28 rounded-lg object-cover bg-black"></video>
                    </div>
                    @endforeach
                </div>
                @endif

                <form action="{{ url('administrator/tentang-desa/sejarah/upload-video') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Judul Video (Opsional)</label>
                        <input type="text" name="judul_video" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10" placeholder="Contoh: Sejarah Berdirinya Desa Kumtura">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">File Video <span class="text-rose-500">*</span></label>
                        <input type="file" name="video" required accept="video/mp4,video/webm,video/ogg"
                            class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                        <p class="text-[10px] text-slate-400 mt-1">Format: MP4, WebM, OGG. Maks 100MB.</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all">
                            <i class="bi bi-cloud-arrow-up mr-1"></i>Upload Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: PENGURUS DESA ADAT
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'pengurus'" x-transition>
        <div class="space-y-5">

            {{-- Bendesa Adat --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
                    <i class="bi bi-person-badge text-primary-light"></i> Bendesa Adat
                </h3>

                <div class="flex flex-col md:flex-row gap-6 mb-6">
                    {{-- Foto Bendesa --}}
                    <div class="flex flex-col items-center gap-3 shrink-0">
                        <div class="h-32 w-32 rounded-2xl bg-slate-100 overflow-hidden border-2 border-slate-200 flex items-center justify-center">
                            @if(!empty($settings['bendesa_foto']))
                                <img src="{{ asset('storage/tentang_desa/pengurus/' . $settings['bendesa_foto']) }}" class="h-full w-full object-cover" alt="Bendesa Adat">
                            @else
                                <i class="bi bi-person-fill text-4xl text-slate-300"></i>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold text-center">Foto Bendesa Adat</p>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Bendesa</p>
                                <p class="font-bold text-slate-700">{{ $settings['bendesa_nama'] ?? '-' }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Telepon</p>
                                <p class="font-bold text-slate-700">{{ $settings['bendesa_no_telp'] ?? '-' }}</p>
                            </div>
                            @if(!empty($settings['bendesa_sambutan']))
                            <div class="md:col-span-2 bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kata Sambutan</p>
                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">{{ strip_tags($settings['bendesa_sambutan']) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ url('administrator/tentang-desa/sejarah/update-pengurus') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-4 border-t border-slate-100">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Bendesa Adat</label>
                            <input type="text" name="nama_bendesa" value="{{ $settings['bendesa_nama'] ?? '' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10"
                                placeholder="Nama lengkap Bendesa Adat">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. Telepon (Opsional)</label>
                            <input type="text" name="no_telp_bendesa" value="{{ $settings['bendesa_no_telp'] ?? '' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kata Sambutan</label>
                            <textarea id="sambutan_editor" name="kata_sambutan" rows="6"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 resize-y"
                                placeholder="Tulis kata sambutan Bendesa Adat...">{{ $settings['bendesa_sambutan'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Foto Bendesa Adat (Opsional)</label>
                            <input type="file" name="foto_bendesa" accept="image/png,image/jpeg,image/jpg"
                                class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 2MB.</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                            <i class="bi bi-save mr-2"></i>Simpan Data Bendesa
                        </button>
                    </div>
                </form>
            </div>

            {{-- Foto Struktur Desa Adat --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
                    <i class="bi bi-diagram-3 text-primary-light"></i> Foto Struktur Desa Adat
                </h3>

                @if(!empty($settings['foto_struktur_desa']))
                <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 mb-5">
                    <img src="{{ asset('storage/tentang_desa/pengurus/' . $settings['foto_struktur_desa']) }}"
                         class="w-full object-contain max-h-[500px]" alt="Struktur Desa Adat">
                </div>
                @else
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-10 text-center mb-5">
                    <i class="bi bi-diagram-3 text-4xl text-slate-300 block mb-2"></i>
                    <p class="text-sm font-bold text-slate-400">Belum ada foto struktur organisasi desa.</p>
                </div>
                @endif

                <form action="{{ url('administrator/tentang-desa/sejarah/upload-struktur') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                        {{ !empty($settings['foto_struktur_desa']) ? 'Ganti Foto Struktur' : 'Upload Foto Struktur' }}
                    </label>
                    <input type="file" name="foto_struktur_desa" required accept="image/png,image/jpeg,image/jpg"
                        class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                    <p class="text-[10px] text-slate-400">Format: JPG, PNG. Maks 5MB. Gunakan gambar landscape untuk hasil terbaik.</p>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all">
                            <i class="bi bi-cloud-arrow-up mr-1"></i>Upload Struktur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: HEADER GALLERY
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'gallery'" x-transition>
        <div class="space-y-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
                    <i class="bi bi-images text-primary-light"></i> Header Image Gallery
                </h3>
                <p class="text-xs text-slate-500 mb-5">Gambar-gambar ini akan ditampilkan sebagai carousel di bagian atas halaman publik Tentang Desa Adat.</p>

                {{-- Existing Images --}}
                @php $gallery = $settings['gallery_desa'] ?? []; @endphp
                @if(count($gallery) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    @foreach($gallery as $index => $image)
                    <div class="relative group rounded-xl overflow-hidden border border-slate-200">
                        <img src="{{ asset('storage/tentang_desa/gallery/' . $image) }}"
                             class="w-full h-32 object-cover"
                             alt="Gallery {{ $index + 1 }}">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <form action="{{ url('administrator/tentang-desa/sejarah/gallery/delete') }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus gambar ini?')">
                                @csrf
                                <input type="hidden" name="filename" value="{{ $image }}">
                                <button type="submit"
                                        class="h-10 w-10 bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors flex items-center justify-center">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                        <div class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                            {{ $index + 1 }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-10 text-center mb-6">
                    <i class="bi bi-images text-4xl text-slate-300 block mb-2"></i>
                    <p class="text-sm font-bold text-slate-400">Belum ada gambar gallery.</p>
                    <p class="text-xs text-slate-400 mt-1">Upload gambar untuk ditampilkan di header halaman Tentang Desa Adat.</p>
                </div>
                @endif

                {{-- Upload Form --}}
                <form action="{{ url('administrator/tentang-desa/sejarah/gallery/store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-3 pt-4 border-t border-slate-100">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                            Upload Gambar Gallery <span class="text-rose-500">*</span>
                        </label>
                        <input type="file"
                               name="gallery_image"
                               required
                               accept="image/jpeg,image/png,image/jpg"
                               class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 5MB. Rekomendasi: 1200x600px (landscape).</p>
                    </div>
                    @error('gallery_image')
                    <p class="text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all">
                            <i class="bi bi-cloud-arrow-up mr-1"></i>Upload Gambar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TAB: PRODUK HUKUM
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'hukum'" x-transition>
        <div class="space-y-5">

            {{-- List Produk Hukum --}}
            @php $produkHukum = $settings['produk_hukum'] ?? []; @endphp
            @if(count($produkHukum) > 0)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-700">Daftar Produk Hukum ({{ count($produkHukum) }})</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($produkHukum as $ph)
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center shrink-0
                            {{ strtolower($ph['ext'] ?? '') === 'pdf' ? 'bg-rose-50 text-rose-500' : 'bg-blue-50 text-blue-500' }}">
                            <i class="bi {{ strtolower($ph['ext'] ?? '') === 'pdf' ? 'bi-file-earmark-pdf' : 'bi-file-earmark-word' }} text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $ph['nama_produk'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ strtoupper($ph['ext'] ?? '') }} &bull; {{ $ph['created_at'] ?? '' }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ asset('storage/tentang_desa/produk_hukum/' . $ph['file']) }}" target="_blank"
                               class="h-8 w-8 flex items-center justify-center bg-slate-50 border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all" title="Unduh">
                                <i class="bi bi-download text-sm"></i>
                            </a>
                            <form action="{{ url('administrator/tentang-desa/sejarah/produk-hukum/delete') }}" method="POST" onsubmit="return confirm('Hapus produk hukum ini?')">
                                @csrf
                                <input type="hidden" name="id" value="{{ $ph['id'] }}">
                                <button type="submit" class="h-8 w-8 flex items-center justify-center bg-white border border-rose-200 text-rose-400 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus">
                                    <i class="bi bi-trash3 text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                <i class="bi bi-file-earmark-text text-4xl text-slate-300 block mb-3"></i>
                <p class="text-sm font-bold text-slate-400">Belum ada produk hukum.</p>
            </div>
            @endif

            {{-- Form Tambah --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
                    <i class="bi bi-plus-circle text-primary-light"></i> Tambah Produk Hukum
                </h3>
                <form action="{{ url('administrator/tentang-desa/sejarah/produk-hukum/store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Produk Hukum <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_produk" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10"
                            placeholder="Contoh: Peraturan Desa No. 1 Tahun 2024">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Upload File <span class="text-rose-500">*</span></label>
                        <input type="file" name="file_produk" required accept=".pdf,.doc,.docx"
                            class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                        <p class="text-[10px] text-slate-400 mt-1">Format: PDF, DOC, DOCX. Maks 10MB.</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                            <i class="bi bi-cloud-arrow-up mr-2"></i>Upload Produk Hukum
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof CKEDITOR !== 'undefined') {
        // Editor Sejarah — dengan image upload
        CKEDITOR.replace('sejarah_editor', {
            toolbar: [
                { name: 'document',    items: ['Source'] },
                { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','-','RemoveFormat'] },
                { name: 'paragraph',   items: ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','JustifyLeft','JustifyCenter','JustifyRight'] },
                { name: 'styles',      items: ['Styles','Format','FontSize'] },
                { name: 'colors',      items: ['TextColor','BGColor'] },
                { name: 'links',       items: ['Link','Unlink'] },
                { name: 'insert',      items: ['Image','Table','HorizontalRule','SpecialChar'] },
                { name: 'tools',       items: ['Maximize'] },
            ],
            height: 450,
            filebrowserUploadUrl: '{{ url('administrator/tentang-desa/sejarah/upload-media') }}',
            filebrowserUploadMethod: 'form',
            extraPlugins: 'uploadimage',
            imageUploadUrl: '{{ url('administrator/tentang-desa/sejarah/upload-media') }}',
            removePlugins: 'elementspath',
            resize_enabled: false,
        });

        // Editor Kata Sambutan — simple toolbar
        CKEDITOR.replace('sambutan_editor', {
            toolbar: [
                { name: 'basicstyles', items: ['Bold','Italic','Underline','-','RemoveFormat'] },
                { name: 'paragraph',   items: ['NumberedList','BulletedList','-','Blockquote'] },
            ],
            height: 200,
            removePlugins: 'elementspath',
            resize_enabled: false,
        });
    }
});
</script>
@endsection
