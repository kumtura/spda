@extends('mobile_layout_public')

@php
    $slides = \App\Models\Gambar\Slides\Slides::where('aktif', '1')->orderBy('id_gambar_home', 'desc')->get();
    $lokers = \App\Models\Loker::with('usaha')->where('status', 'Buka')->orderBy('id_loker', 'desc')->take(3)->get();
@endphp

@section('content')

<!-- Hero Image Section -->
<div class="relative w-full h-[300px] bg-slate-900 overflow-hidden" x-data="{ activeSlide: 0 }" x-init="
    let slides = document.querySelectorAll('[data-slide]');
    if(slides.length > 1) setInterval(() => { activeSlide = (activeSlide + 1) % slides.length }, 5000);
">
    @if(count($slides) > 0)
        @foreach($slides as $i => $slide)
            <div data-slide x-show="activeSlide === {{ $i }}" 
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0">
                <img src="{{ asset('GambarSlides/'.$slide->image_name) }}" class="w-full h-full object-cover opacity-70" alt="{{ $slide->title }}">
            </div>
        @endforeach
    @else
        <img src="{{ asset('storage/login_bg/back_home.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1554261234-7cecb1e22701?q=80&w=800&auto=format&fit=crop'" class="w-full h-full object-cover opacity-60" alt="Hero Fallback">
    @endif
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/40 to-transparent"></div>
    
    <!-- Hero Text -->
    @if(count($slides) > 0)
        @foreach($slides as $i => $slide)
            <div data-slide x-show="activeSlide === {{ $i }}"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute bottom-10 left-0 right-0 px-8 flex flex-col items-center text-center z-10 pointer-events-none">
                @if(file_exists(public_path('storage/logos/logo.png')))
                    <img src="{{ asset('storage/logos/logo.png') }}" class="h-20 w-20 mb-4 drop-shadow-[0_0_15px_rgba(255,255,255,0.3)] object-contain animate-fade-in-up" alt="Logo Desa">
                @endif
                <h1 class="text-white text-4xl md:text-5xl font-black tracking-tighter drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)] mb-2 leading-none uppercase">{{ $slide->title ?: ($village['name'] ?? 'SPDA') }}</h1>
                <p class="text-white/90 text-[10px] md:text-xs drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] font-bold uppercase tracking-widest">{{ $slide->deskripsi ?: 'Sistem Punia Desa Adat' }}</p>
            </div>
        @endforeach
    @else
        <div class="absolute bottom-10 left-0 right-0 px-8 flex flex-col items-center text-center z-10 pointer-events-none">
            @if(file_exists(public_path('storage/logos/logo.png')))
                <img src="{{ asset('storage/logos/logo.png') }}" class="h-20 w-20 mb-4 drop-shadow-[0_0_15px_rgba(255,255,255,0.3)] object-contain animate-fade-in-up" alt="Logo Desa">
            @endif
            <h1 class="text-white text-4xl md:text-5xl font-black tracking-tighter drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)] mb-2 leading-none uppercase">{{ $village['name'] ?? 'SPDA' }}</h1>
            <p class="text-white/90 text-[10px] md:text-xs drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] font-bold uppercase tracking-widest">Sistem Punia Desa Adat</p>
        </div>
    @endif

    <!-- Slide Indicators -->
    @if(count($slides) > 1)
    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5">
        @foreach($slides as $i => $slide)
            <div class="h-1 rounded-full transition-all duration-500" 
                 :class="activeSlide === {{ $i }} ? 'w-6 bg-[#00a6eb]' : 'w-1.5 bg-white/30'"></div>
        @endforeach
    </div>
    @endif
</div>

<!-- Main Content Area -->
<div class="bg-white rounded-t-3xl -mt-4 relative z-20 px-4 pt-8 pb-10 space-y-8">
    
    <!-- Menu -->
    <div class="grid grid-cols-4 gap-2">
        <a href="{{ route('public.punia') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-wallet2 text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Punia</span>
        </a>
        <a href="{{ route('public.donasi') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-heart-pulse text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Donasi</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-2 group opacity-50 cursor-not-allowed">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white">
                <i class="bi bi-people text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Karyawan</span>
        </a>
        <a href="{{ route('public.berita') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-journal-text text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Berita</span>
        </a>
    </div>



    <!-- Donasi Cards -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-bold text-slate-800">Program Donasi Berkelanjutan</h3>
            <a href="{{ route('public.donasi') }}" class="text-[10px] font-black text-[#00a6eb] tracking-widest hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @forelse($programs as $prog)
                <a href="{{ route('public.donasi.detail', $prog->id_program_donasi) }}" class="block bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                    <div class="h-32 bg-slate-50 relative flex items-center justify-center overflow-hidden">
                        @if($prog->foto)
                            <img src="{{ asset('storage/program_donasi/'.$prog->foto) }}" class="w-full h-full object-cover" alt="{{ $prog->nama_program }}">
                        @else
                            <i class="bi bi-image text-3xl text-slate-200"></i>
                        @endif
                        <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded-sm tracking-widest uppercase">{{ $prog->kategori->nama_kategori ?? 'Umum' }}</span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 group-hover:text-[#00a6eb] transition-colors">{{ $prog->nama_program }}</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[9px] text-slate-400 font-medium mb-0.5">Terkumpul</p>
                                <p class="text-[11px] font-bold text-[#00a6eb]">Rp {{ number_format($prog->terkumpul, 0, ',', '.') }}</p>
                            </div>
                            <span class="bg-slate-50 text-slate-600 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors px-4 py-1.5 rounded-full text-[10px] border border-slate-200 font-bold shadow-sm">Donasi</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-6 text-center">
                    <p class="text-xs font-bold text-slate-400">Belum ada program aktif.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="h-1.5 w-full bg-slate-100 rounded-full"></div>

    <!-- Lowongan Kerja (Loker) -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-bold text-slate-800">Lowongan Kerja (Loker)</h3>
            @if(count($lokers) > 0)
                <span class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">{{ count($lokers) }} Aktif</span>
            @endif
        </div>

        @if(count($lokers) > 0)
            <div class="space-y-3">
                @foreach($lokers as $loker)
                    <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition-all group flex gap-4">
                        <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                            @if($loker->usaha && $loker->usaha->foto)
                                <img src="{{ asset('logousaha/'.$loker->usaha->foto) }}" class="h-full w-full object-cover" alt="Logo Usaha">
                            @else
                                <i class="bi bi-building text-slate-400 text-xl group-hover:text-[#00a6eb] transition-colors"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-black text-slate-800 leading-snug mb-0.5 group-hover:text-[#00a6eb] transition-colors">{{ $loker->judul }}</h4>
                            <p class="text-[10px] text-slate-500 font-medium mb-3">{{ $loker->usaha->title ?? 'SPDA Unit Usaha' }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 uppercase tracking-widest">
                                    <i class="bi bi-circle-fill text-[5px] mr-1 align-middle"></i> Buka
                                </span>
                                <a href="{{ url('login') }}" class="text-[9px] font-black text-[#00a6eb] uppercase tracking-widest hover:underline flex items-center gap-1">Lamar <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-6 text-center">
                <i class="bi bi-briefcase text-3xl text-slate-300 mb-2 block"></i>
                <p class="text-xs font-bold text-slate-500">Belum ada lowongan kerja aktif.</p>
                <p class="text-[10px] text-slate-400 mt-1">Nantikan kesempatan karir dari unit usaha SPDA.</p>
            </div>
        @endif
    </div>
</div>
@endsection
