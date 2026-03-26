@extends('index')

@section('content')
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

        <!-- Hero Slider & Visual Assets -->
        <div class="bg-white rounded-4xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="bi bi-collection-play-fill text-primary-light"></i> Pengaturan Visual Beranda
            </h3>
            
            <div class="space-y-6">
                <p class="text-[11px] text-slate-500 leading-relaxed">Kelola aset visual yang muncul pada landing page utama, termasuk slider gambar dan kategori promo/informasi.</p>
                
                <div class="grid grid-cols-1 gap-4">
                    <!-- Quick Link: Slider Images -->
                    <a href="{{ url('administrator/datagambar_slides') }}" class="group flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-lg hover:border-primary-light/20 transition-all">
                        <div class="h-12 w-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-light group-hover:bg-primary-light group-hover:text-white transition-colors">
                            <i class="bi bi-images text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Manajemen Slide</h4>
                            <p class="text-[10px] text-slate-400 font-bold">Unggah & atur urutan gambar slider utama.</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-primary-light transition-colors"></i>
                    </a>

                    <!-- Quick Link: Slider Categories -->
                    <a href="{{ url('administrator/datakategori_slides') }}" class="group flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-lg hover:border-primary-light/20 transition-all">
                        <div class="h-12 w-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-light group-hover:bg-primary-light group-hover:text-white transition-colors">
                            <i class="bi bi-tags-fill text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Kategori Slide</h4>
                            <p class="text-[10px] text-slate-400 font-bold">Kelompokkan gambar berdasarkan promosi/acara.</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-primary-light transition-colors"></i>
                    </a>
                </div>
                
                <div class="p-4 bg-sky-50 rounded-2xl border border-sky-100">
                    <div class="flex gap-3">
                        <i class="bi bi-info-circle-fill text-sky-500 mt-0.5"></i>
                        <p class="text-[10px] text-sky-700 font-bold leading-relaxed lowercase">Gunakan manajemen terpusat ini untuk memastikan visual website tetap konsisten dan menarik bagi pengunjung.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
