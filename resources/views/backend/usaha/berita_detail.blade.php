@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">

    <!-- Hero Section -->
    <div class="relative h-[200px] bg-slate-100 flex items-center justify-center overflow-hidden">
        @if($berita->foto)
            <img src="{{ asset('storage/berita/foto/'.$berita->foto) }}" class="w-full h-full object-cover" alt="{{ $berita->judul }}">
        @else
            <i class="bi bi-newspaper text-[60px] text-slate-200"></i>
        @endif
        <a href="{{ url('administrator/usaha/berita') }}" class="absolute top-5 left-4 h-9 w-9 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center text-slate-600 border border-white/50 active:scale-90 transition-transform">
            <i class="bi bi-chevron-left text-lg"></i>
        </a>
    </div>

    <!-- Main Content -->
    <div class="px-4 -mt-5 relative z-10 space-y-5">

        <!-- Title Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
            @if($berita->kategori)
            <span class="inline-block text-[#00a6eb] text-[8px] font-bold uppercase tracking-wider">{{ $berita->kategori->nama_kategori }}</span>
            @endif
            <h1 class="text-base font-bold text-slate-800 leading-tight">{{ $berita->judul }}</h1>
            <div class="flex items-center gap-3 text-[10px] text-slate-400">
                <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y') }}</span>
                @if($berita->penulis)
                <span><i class="bi bi-person"></i> {{ $berita->penulis }}</span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-xs text-slate-700 leading-relaxed prose prose-sm max-w-none">
                {!! nl2br(e($berita->isi_berita)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
