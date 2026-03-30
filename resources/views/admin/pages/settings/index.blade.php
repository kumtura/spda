@extends('index')

@section('isi_menu')
<div class="px-6 space-y-6">
    <!-- Header Page -->
    <div class="bg-white/95 backdrop-blur-xl border border-white/20 p-6 rounded-3xl shadow-lg border-l-4 border-l-primary-light animate-in fade-in slide-in-from-bottom-4 duration-700 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 h-32 w-32 bg-primary-light/5 rounded-full blur-2xl"></div>
        <div class="relative z-10 flexitems-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none mb-2">Pengaturan Website</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-none">Manajemen Identitas Visual Sistem</p>
            </div>
            <div class="hidden sm:block">
                <i class="bi bi-gear-fill text-5xl text-slate-100/50"></i>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200" role="alert">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
    @endif
    
     @if(session('error'))
    <div class="p-4 mb-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200" role="alert">
        <span class="font-bold">Gagal!</span> {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Logo Settings -->
        <div class="bg-white rounded-4xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="bi bi-image text-primary-light"></i> Logo Sistem Terpusat
            </h3>
            
            <div class="flex items-start gap-6 mb-6">
                <div class="h-24 w-24 bg-slate-50 border border-dashed border-slate-300 rounded-2xl flex items-center justify-center p-3 shrink-0">
                    <img src="{{ asset('storage/logos/logo.png') }}" class="max-h-full max-w-full object-contain" alt="Current Logo" onerror="this.src='{{ asset('storage/login_bg/donasi.png') }}'">
                </div>
                <div>
                    <p class="text-[11px] text-slate-500 mb-2 leading-relaxed">Logo saat ini. Logo yang diunggah akan otomatis menimpa logo ini dan diperbarui di seluruh platform (Landing Page, Login, Sidebar).</p>
                    <p class="text-[10px] bg-sky-50 text-sky-600 px-3 py-1.5 rounded-lg inline-flex items-center gap-1 font-medium border border-sky-100">
                        <i class="bi bi-info-circle-fill"></i> Format: JPG, PNG. Maks File: 2MB. Resolusi: Persegi
                    </p>
                </div>
            </div>

            <form action="{{ url('administrator/settings/update_logo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2 text-xs font-bold text-slate-700" for="logo_file">Pilih Logo Baru</label>
                    <input class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark transition-all" id="logo_file" type="file" name="logo" required accept="image/png, image/jpeg, image/svg+xml">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-blue-500/20 transition-colors flex items-center gap-2">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Unggah logo
                    </button>
                </div>
            </form>
        </div>

        <!-- Hero Slideshow Manager -->
        <div class="bg-white rounded-4xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="bi bi-collection-play-fill text-primary-light"></i> Slideshow Hero Beranda
            </h3>

            @php
                $heroSlides = \App\Models\Gambar\Slides\Slides::where('aktif', '1')->orderBy('id_gambar_home', 'desc')->get();
            @endphp
            
            <!-- Current Slides -->
            @if($heroSlides->count() > 0)
            <div class="mb-6 space-y-3">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Slide Aktif ({{ $heroSlides->count() }})</p>
                @foreach($heroSlides as $slide)
                <div class="flex flex-col gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 group">
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-24 rounded-xl overflow-hidden shrink-0 bg-slate-200 border border-slate-100">
                            <img src="{{ asset('GambarSlides/'.$slide->image_name) }}" class="h-full w-full object-cover" alt="{{ $slide->title }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Informasi Slide</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $slide->title ?: 'Tanpa Judul' }}</p>
                        </div>
                        <form action="{{ url('administrator/settings/delete_hero_slide') }}" method="POST" onsubmit="return confirm('Hapus slide ini?')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $slide->id_gambar_home }}">
                            <button type="submit" class="h-8 w-8 bg-white border border-rose-200 text-rose-400 rounded-lg flex items-center justify-center hover:bg-rose-50 transition-colors shadow-sm">
                                <i class="bi bi-trash3 text-sm"></i>
                            </button>
                        </form>
                    </div>
                    
                    <form action="{{ url('administrator/settings/update_hero_slide_metadata') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-slate-200/50">
                        @csrf
                        <input type="hidden" name="id" value="{{ $slide->id_gambar_home }}">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Judul Slide</label>
                            <input type="text" name="title" value="{{ $slide->title }}" placeholder="Tulis judul..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-[11px] font-bold text-slate-700 focus:border-primary-light outline-none transition-all">
                        </div>
                        <div class="space-y-1 relative">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Deskripsi</label>
                            <div class="flex gap-2">
                                <input type="text" name="deskripsi" value="{{ $slide->deskripsi }}" placeholder="Tulis deskripsi..." class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-[11px] font-bold text-slate-600 focus:border-primary-light outline-none transition-all">
                                <button type="submit" title="Simpan Perubahan" class="h-8 w-8 bg-primary-light text-white rounded-lg flex items-center justify-center hover:bg-primary-dark transition-all transform active:scale-90 shadow-lg shadow-blue-100 shrink-0">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-center">
                <i class="bi bi-images text-2xl text-slate-300 mb-1 block"></i>
                <p class="text-xs text-slate-400 font-bold">Belum ada slide. Upload gambar pertama Anda.</p>
            </div>
            @endif

            <!-- Upload New -->
            <form action="{{ url('administrator/settings/upload_hero_slide') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Slide (Opsional)</label>
                        <input type="text" name="hero_title" placeholder="Mis: Nyepi 2026" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Deskripsi Singkat / Subtitle</label>
                        <input type="text" name="hero_deskripsi" placeholder="Teks kecil di bawah judul" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar Slide</label>
                        <input type="file" name="hero_image" required accept="image/png,image/jpeg,image/webp" class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark transition-all">
                        <p class="text-[9px] text-slate-400 mt-1 px-1">Format: JPG, PNG, WEBP. Maks: 5MB. Resolusi landscape untuk hasil terbaik.</p>
                    </div>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-blue-500/20 transition-colors flex items-center gap-2">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Tambah Slide
                    </button>
                </div>
            </form>
        </div>

        <!-- Village Data Settings -->
        <div class="bg-white rounded-4xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition duration-300 col-span-1 md:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="bi bi-geo-alt-fill text-primary-light"></i> Informasi Identitas Desa Adat
            </h3>
            
            <form action="{{ url('administrator/settings/update_village') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @csrf
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Desa Adat</label>
                        <input type="text" name="village_name" value="{{ $village['name'] ?? 'SPDA' }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Bendesa Adat</label>
                        <input type="text" name="bendesa_name" value="{{ $village['bendesa'] ?? '' }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap Kantor</label>
                        <textarea name="village_address" rows="4" required
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all resize-none">{{ $village['address'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="md:col-span-2 flex justify-end pt-4">
                    <button type="submit" class="bg-slate-900 hover:bg-primary-dark text-white px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl transition-all transform hover:-translate-y-1">
                        Simpan Identitas Desa <i class="bi bi-check-lg ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Bank Account Settings -->
        <div class="bg-white rounded-4xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition duration-300 col-span-1 md:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="bi bi-bank text-primary-light"></i> Rekening Bank Desa Adat
            </h3>
            
            <form action="{{ url('administrator/settings/update_bank_accounts') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Informasi Rekening</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Rekening ini akan ditampilkan kepada masyarakat saat melakukan transfer manual. Pastikan data rekening benar dan aktif.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- BCA -->
                    <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-8 w-12 bg-white rounded border border-slate-300 flex items-center justify-center">
                                <span class="text-[10px] font-black text-slate-700">BCA</span>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Bank Central Asia</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Nomor Rekening</label>
                            <input type="text" name="bank_bca_number" value="{{ $village['bank_bca_number'] ?? '' }}" placeholder="1234567890"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Atas Nama</label>
                            <input type="text" name="bank_bca_name" value="{{ $village['bank_bca_name'] ?? '' }}" placeholder="Nama Pemilik Rekening"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                    </div>

                    <!-- BNI -->
                    <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-8 w-12 bg-white rounded border border-slate-300 flex items-center justify-center">
                                <span class="text-[10px] font-black text-slate-700">BNI</span>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Bank Negara Indonesia</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Nomor Rekening</label>
                            <input type="text" name="bank_bni_number" value="{{ $village['bank_bni_number'] ?? '' }}" placeholder="0987654321"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Atas Nama</label>
                            <input type="text" name="bank_bni_name" value="{{ $village['bank_bni_name'] ?? '' }}" placeholder="Nama Pemilik Rekening"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                    </div>

                    <!-- Mandiri -->
                    <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-8 w-12 bg-white rounded border border-slate-300 flex items-center justify-center">
                                <span class="text-[10px] font-black text-slate-700">MANDIRI</span>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Bank Mandiri</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Nomor Rekening</label>
                            <input type="text" name="bank_mandiri_number" value="{{ $village['bank_mandiri_number'] ?? '' }}" placeholder="1122334455"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Atas Nama</label>
                            <input type="text" name="bank_mandiri_name" value="{{ $village['bank_mandiri_name'] ?? '' }}" placeholder="Nama Pemilik Rekening"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                    </div>

                    <!-- BRI -->
                    <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-8 w-12 bg-white rounded border border-slate-300 flex items-center justify-center">
                                <span class="text-[10px] font-black text-slate-700">BRI</span>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Bank Rakyat Indonesia</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Nomor Rekening</label>
                            <input type="text" name="bank_bri_number" value="{{ $village['bank_bri_number'] ?? '' }}" placeholder="5566778899"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1">Atas Nama</label>
                            <input type="text" name="bank_bri_name" value="{{ $village['bank_bri_name'] ?? '' }}" placeholder="Nama Pemilik Rekening"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-slate-900 hover:bg-primary-dark text-white px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl transition-all transform hover:-translate-y-1">
                        Simpan Rekening Bank <i class="bi bi-check-lg ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
