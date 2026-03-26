@extends('mobile_layout_public')

@section('content')
<!-- Top Navbar (GoPererenan Style) -->
<div class="bg-[#00a6eb] w-full h-16 flex items-center px-4 gap-3 sticky top-0 z-50">
    <!-- Dynamic System Logo -->
    <div class="h-10 w-10 shrink-0 bg-white/20 rounded-md p-1 shadow-inner flex items-center justify-center">
        @php
            $logoPath = 'storage/logos/logo.png';
            if (!file_exists(public_path($logoPath))) {
                $logoPath = 'storage/login_bg/donasi.png';
            }
        @endphp
        <img src="{{ asset($logoPath) }}" class="max-h-full max-w-full object-contain drop-shadow" alt="Logo">
    </div>
    
    <!-- Search Bar -->
    <div class="flex-1">
        <div class="bg-white/20 backdrop-blur-md rounded-full px-4 py-2 flex items-center gap-2 border border-white/20 h-10">
            <input type="text" class="bg-transparent border-0 text-white text-xs w-full focus:ring-0 placeholder-white/70 p-0" placeholder="Masukkan Pencarian Anda Di Sini...">
        </div>
    </div>
</div>

<!-- Hero Image Section (Dynamic Gallery) -->
<div class="relative w-full h-[300px] bg-slate-900 overflow-hidden" x-data="{ 
    activeSlide: 0,
    slides: [],
    init() {
        @php
            $galleryPath = public_path('storage/gallery');
            $images = is_dir($galleryPath) ? array_diff(scandir($galleryPath), ['.', '..']) : [];
            $imageUrls = array_map(function($img) { return asset('storage/gallery/' . $img); }, array_values($images));
        @endphp
        this.slides = @json($imageUrls);
        if(this.slides.length > 1) {
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            }, 5000);
        }
    }
}">
    <!-- Slides -->
    <template x-if="slides.length > 0">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute inset-0">
                <img :src="slide" class="w-full h-full object-cover opacity-70" alt="Hero Gallery">
            </div>
        </template>
    </template>

    <!-- Fallback if empty -->
    <template x-if="slides.length === 0">
        <img src="{{ asset('storage/login_bg/back_home.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1554261234-7cecb1e22701?q=80&w=800&auto=format&fit=crop'" class="w-full h-full object-cover opacity-60" alt="Hero Fallback">
    </template>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/40 to-transparent"></div>
    
    <!-- Hero Text (Premium Polish) -->
    <div class="absolute bottom-10 left-0 right-0 px-8 text-center" x-transition>
        <h1 class="text-white text-5xl font-black tracking-tighter shadow-black drop-shadow-2xl mb-2 leading-none">SPDA</h1>
        <p class="text-white/80 text-xs shadow-black drop-shadow-md font-bold uppercase tracking-widest">Sistem Pengelolaan Desa Adat</p>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5" x-show="slides.length > 1">
        <template x-for="(slide, index) in slides" :key="index">
            <div class="h-1 rounded-full transition-all duration-500" 
                 :class="activeSlide === index ? 'w-6 bg-[#00a6eb]' : 'w-1.5 bg-white/30'"></div>
        </template>
    </div>
</div>

<!-- Main Content Area -->
<div class="bg-white rounded-t-3xl -mt-4 relative z-20 px-4 pt-8 pb-10 space-y-8">
    
    <!-- 4-Grid Menu (GoPererenan Style) -->
    <div class="grid grid-cols-4 gap-2">
        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="h-14 w-14 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-wallet2 text-3xl text-[#00a6eb]"></i>
            </div>
            <span class="text-xs text-slate-500 font-medium">Punia</span>
        </a>
        
        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="h-14 w-14 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-heart-pulse text-3xl text-[#00a6eb]"></i>
            </div>
            <span class="text-xs text-slate-500 font-medium">Donation</span>
        </a>
        
        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="h-14 w-14 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-people text-3xl text-[#00a6eb]"></i>
            </div>
            <span class="text-xs text-slate-500 font-medium">Employee</span>
        </a>
        
        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="h-14 w-14 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-journal-text text-3xl text-[#00a6eb]"></i>
            </div>
            <span class="text-xs text-slate-500 font-medium">Blog</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="h-1.5 w-full bg-slate-100 rounded-full"></div>

    <!-- Pilihan Donasi / Content (Mockup based on reference) -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4 px-1">Program Donasi Berkelanjutan</h3>
        
        <div class="space-y-4">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="h-32 bg-slate-200 relative">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=500&auto=format&fit=crop" class="w-full h-full object-cover" alt="Image">
                    <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded-sm tracking-widest uppercase">Pembangunan</span>
                </div>
                <div class="p-3">
                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2">Bantuan Alat Sarana & Prasarana Pura Desa</h4>
                    <div class="flex items-center justify-between mt-auto">
                        <div>
                            <p class="text-[9px] text-slate-400 font-medium mb-0.5">Terkumpul</p>
                            <p class="text-[11px] font-bold text-[#00a6eb]">Rp 15.000.000</p>
                        </div>
                        <button class="bg-slate-50 text-slate-600 hover:bg-[#00a6eb] hover:text-white transition-colors px-4 py-1.5 rounded-full text-[10px] border border-slate-200 font-bold shadow-sm">
                            Donasi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="h-32 bg-slate-200 relative">
                    <img src="https://images.unsplash.com/photo-1518544866330-9755abfc11da?w=500&auto=format&fit=crop" class="w-full h-full object-cover" alt="Image">
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-[8px] font-bold px-2 py-1 rounded-sm tracking-widest uppercase">Mendesak</span>
                </div>
                <div class="p-3">
                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2">Donasi Bantuan Bencana Alam Lokal</h4>
                    <div class="flex items-center justify-between mt-auto">
                        <div>
                            <p class="text-[9px] text-slate-400 font-medium mb-0.5">Terkumpul</p>
                            <p class="text-[11px] font-bold text-[#00a6eb]">Rp 4.500.000</p>
                        </div>
                        <button class="bg-slate-50 text-slate-600 hover:bg-[#00a6eb] hover:text-white transition-colors px-4 py-1.5 rounded-full text-[10px] border border-slate-200 font-bold shadow-sm">
                            Donasi
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
