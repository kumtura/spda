@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-6 pb-24 space-y-6">

    <!-- Back -->
    <a href="{{ route('public.berita') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-[#00a6eb] text-xs font-bold transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Cover Image -->
    @if($berita->foto)
        <div class="h-48 rounded-2xl overflow-hidden bg-slate-100">
            <img src="{{ asset('storage/berita/foto/'.$berita->foto) }}" class="w-full h-full object-cover" alt="{{ $berita->judul_berita }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="w-full h-full items-center justify-center bg-slate-50 relative" style="display:none;">
                <i class="bi bi-image text-3xl text-slate-200"></i>
            </div>
        </div>
    @endif

    <!-- Article Header -->
    <div>
        <p class="text-[9px] text-[#00a6eb] font-bold uppercase tracking-wider mb-2">{{ \Carbon\Carbon::parse($berita->tanggal_berita)->translatedFormat('d F Y') }}</p>
        <h1 class="text-xl font-black text-slate-800 leading-tight">{{ $berita->judul_berita }}</h1>
    </div>

    <!-- Article Content -->
    <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed">
        {!! $berita->isi_berita !!}
    </div>

    <div class="h-px w-full bg-slate-100"></div>

    <!-- Recent Posts -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4">Berita Lainnya</h3>
        <div class="space-y-3">
            @foreach($recent_berita as $recent)
                @if($recent->id_berita != $berita->id_berita)
                    <a href="{{ route('public.berita.detail', $recent->id_berita) }}" class="flex gap-3 items-center group">
                        <div class="h-14 w-14 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                            @if($recent->foto)
                                <img src="{{ asset('storage/berita/foto/'.$recent->foto) }}" class="h-full w-full object-cover" alt="">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <i class="bi bi-image text-slate-200"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-slate-800 leading-snug group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $recent->judul_berita }}</h4>
                            <p class="text-[9px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($recent->tanggal_berita)->translatedFormat('d F Y') }}</p>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
