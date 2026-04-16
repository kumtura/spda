@extends('mobile_layout_public')

@php
    $slides = \App\Models\Gambar\Slides\Slides::where('aktif', '1')->orderBy('id_gambar_home', 'desc')->get();
    $lokers = \App\Models\Loker::with('usaha.detail', 'usaha.kategori')->where('status', 'Buka')->orderBy('id_loker', 'desc')->take(3)->get();
    $objekWisata = \App\Models\ObjekWisata::where('aktif', '1')->where('status', 'aktif')->orderBy('created_at', 'desc')->take(5)->get();
@endphp

@section('content')

<!-- Hero Image Section -->
<div class="relative w-full h-[300px] bg-slate-900 overflow-hidden" x-data="{ activeSlide: 0 }" x-init="
    if({{ count($slides) }} > 1) setInterval(() => { activeSlide = (activeSlide + 1) % {{ count($slides) }} }, 5000);
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
        <a href="{{ route('public.tentang_desa') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-info-circle text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold text-center">Tentang Desa</span>
        </a>
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
        <a href="{{ route('public.wisata') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-ticket-perforated text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Objek Wisata</span>
        </a>
    </div>

    <!-- Row 2 Menu -->
    <div class="grid grid-cols-4 gap-2 -mt-4">
        <a href="{{ route('public.agenda') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-calendar3 text-xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold text-center">Agenda Desa Adat</span>
        </a>
        <a href="{{ route('public.krama_tamiu') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-people text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold text-center">Krama Tamiu</span>
        </a>
        <a href="{{ route('public.unit_usaha') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-shop text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Unit Usaha</span>
        </a>
        <a href="{{ route('public.berita') }}" class="flex flex-col items-center gap-2 group">
            <div class="h-12 w-12 rounded-full border border-slate-100 shadow-sm flex items-center justify-center bg-white group-hover:bg-slate-50 transition-colors">
                <i class="bi bi-newspaper text-2xl text-[#00a6eb]"></i>
            </div>
            <span class="text-[10px] text-slate-500 font-bold">Berita</span>
        </a>
    </div>

    <!-- Donasi Cards - Horizontal Scroll -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-bold text-slate-800">Program Donasi</h3>
            <a href="{{ route('public.donasi') }}" class="text-[10px] font-bold text-[#00a6eb] hover:underline">Lihat Semua</a>
        </div>
        <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2 -mx-4 px-4">
            @forelse($programs as $prog)
                <a href="{{ route('public.donasi.detail', $prog->id_program_donasi) }}" class="flex-shrink-0 w-64 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                    <div class="h-32 bg-slate-50 relative flex items-center justify-center overflow-hidden">
                        @if($prog->foto)
                            <img src="{{ asset('storage/program_donasi/'.$prog->foto) }}" class="w-full h-full object-cover" alt="{{ $prog->nama_program }}">
                        @else
                            <i class="bi bi-image text-3xl text-slate-200"></i>
                        @endif
                        <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded-sm uppercase">{{ $prog->kategori->nama_kategori ?? 'Umum' }}</span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $prog->nama_program }}</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[9px] text-slate-400 mb-0.5">Terkumpul</p>
                                <p class="text-[11px] font-bold text-[#00a6eb]">Rp {{ number_format($prog->terkumpul, 0, ',', '.') }}</p>
                            </div>
                            <span class="bg-slate-50 text-slate-600 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors px-3 py-1.5 rounded-full text-[10px] font-bold">Donasi</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex-shrink-0 w-full bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-6 text-center">
                    <p class="text-xs font-bold text-slate-400">Belum ada program aktif.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Objek Wisata - Horizontal Scroll -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-bold text-slate-800">Objek Wisata</h3>
            <a href="{{ route('public.wisata') }}" class="text-[10px] font-bold text-[#00a6eb] hover:underline">Lihat Semua</a>
        </div>
        <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2 -mx-4 px-4">
            @forelse($objekWisata as $objek)
                <a href="{{ route('public.wisata.detail', $objek->slug) }}" class="flex-shrink-0 w-64 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                    <div class="h-32 bg-slate-50 relative flex items-center justify-center overflow-hidden">
                        @if($objek->foto)
                            <img src="{{ asset('storage/wisata/'.$objek->foto) }}" class="w-full h-full object-cover" alt="{{ $objek->nama_objek }}">
                        @else
                            <i class="bi bi-image text-3xl text-slate-200"></i>
                        @endif
                        <span class="absolute top-2 right-2 bg-[#00a6eb] text-white text-[8px] font-bold px-2 py-1 rounded-sm uppercase">Aktif</span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $objek->nama_objek }}</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[9px] text-slate-400 mb-0.5">Mulai dari</p>
                                @php
                                    $minPrice = $objek->kategoriTiket->min('harga') ?? $objek->harga_tiket ?? 0;
                                @endphp
                                <p class="text-[11px] font-bold text-[#00a6eb]">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                            </div>
                            <span class="bg-slate-50 text-slate-600 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors px-3 py-1.5 rounded-full text-[10px] font-bold">Beli Tiket</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex-shrink-0 w-full bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-6 text-center">
                    <i class="bi bi-ticket-perforated text-3xl text-slate-300 mb-2 block"></i>
                    <p class="text-xs font-bold text-slate-400">Belum ada objek wisata.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Berita - Horizontal Scroll -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-bold text-slate-800">Berita Terkini</h3>
            <a href="{{ route('public.berita') }}" class="text-[10px] font-bold text-[#00a6eb] hover:underline">Lihat Semua</a>
        </div>
        <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2 -mx-4 px-4">
            @forelse($berita as $news)
                <a href="{{ route('public.berita.detail', $news->id_berita) }}" class="flex-shrink-0 w-72 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                    <div class="h-40 bg-slate-50 relative flex items-center justify-center overflow-hidden">
                        @if($news->foto)
                            <img src="{{ asset('storage/berita/foto/'.$news->foto) }}" class="w-full h-full object-cover" alt="{{ $news->judul_berita }}">
                        @else
                            <i class="bi bi-newspaper text-3xl text-slate-200"></i>
                        @endif
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $news->judul_berita }}</h4>
                        <p class="text-[10px] text-slate-500 line-clamp-2 mb-2">{{ strip_tags($news->isi_berita) }}</p>
                        <div class="flex items-center justify-between text-[9px] text-slate-400">
                            <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($news->tanggal_berita)->translatedFormat('d M Y') }}</span>
                            <span class="text-[#00a6eb] font-bold">Baca →</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex-shrink-0 w-full bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-6 text-center">
                    <p class="text-xs font-bold text-slate-400">Belum ada berita.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Lowongan Kerja (Loker) -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-bold text-slate-800">Lowongan Kerja</h3>
            @if(count($lokers) > 0)
                <a href="{{ route('public.loker') }}" class="text-[10px] font-bold text-[#00a6eb] hover:underline">Lihat Semua</a>
            @endif
        </div>

        @if(count($lokers) > 0)
            <div class="space-y-3">
                @foreach($lokers as $loker)
                    <a href="{{ route('public.loker.detail', $loker->id_loker) }}" class="block bg-white rounded-xl border border-slate-100 p-4 hover:border-slate-200 hover:shadow-sm transition-all group">
                        <div class="flex gap-3">
                            <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                                @if($loker->usaha && $loker->usaha->detail && $loker->usaha->detail->logo)
                                    @php
                                        $logoPath = file_exists(public_path('usaha/icon/'.$loker->usaha->detail->logo)) 
                                            ? 'usaha/icon/'.$loker->usaha->detail->logo 
                                            : 'storage/usaha/icon/'.$loker->usaha->detail->logo;
                                    @endphp
                                    <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo Usaha">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-slate-50 text-slate-300">
                                        <i class="bi bi-building text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 leading-tight mb-1 group-hover:text-[#00a6eb] transition-colors">{{ $loker->judul }}</h4>
                                <p class="text-xs text-slate-500 mb-2">{{ $loker->usaha->detail->nama_usaha ?? 'Unit Usaha' }}</p>
                                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                    @if($loker->usaha && $loker->usaha->kategori)
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-tag text-[9px]"></i>
                                        {{ $loker->usaha->kategori->nama_kategori_usaha }}
                                    </span>
                                    @endif
                                    <span>•</span>
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-clock text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($loker->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="shrink-0 self-center">
                                <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-colors"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-6 text-center">
                <i class="bi bi-briefcase text-3xl text-slate-300 mb-2 block"></i>
                <p class="text-xs font-medium text-slate-500">Belum ada lowongan kerja aktif.</p>
                <p class="text-[10px] text-slate-400 mt-1">Nantikan kesempatan karir dari unit usaha SPDA.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
