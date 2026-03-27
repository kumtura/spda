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

    <!-- Share Section -->
    <div class="pt-6 border-t border-slate-100 space-y-4">
        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Bagikan Berita</h4>
        <div class="flex items-center gap-3">
            <a href="https://wa.me/?text={{ urlencode($berita->judul_berita . ' ' . url()->current()) }}" target="_blank" class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center transition-all active:scale-90">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center transition-all active:scale-90">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul_berita) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="h-10 w-10 bg-sky-50 text-sky-500 rounded-xl flex items-center justify-center transition-all active:scale-90">
                <i class="bi bi-twitter-x"></i>
            </a>
            <button onclick="navigator.clipboard.writeText('{{ url()->current() }}').then(() => alert('Link berhasil disalin!'))" class="h-10 w-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center transition-all active:scale-90 ml-auto">
                <i class="bi bi-link-45deg text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="pt-6 space-y-6 border-t border-slate-100">
        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
            Komentar 
            <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[9px]">{{ $berita->komentar ? $berita->komentar->count() : 0 }}</span>
        </h4>

        <!-- Comment Input -->
        <form action="{{ route('public.berita.komentar', $berita->id_berita) }}" method="POST" class="flex gap-3 items-start">
            @csrf
            <div class="h-10 w-10 rounded-xl bg-slate-100 shrink-0 flex items-center justify-center text-slate-300">
                <i class="bi bi-person-fill text-xl"></i>
            </div>
            <div class="flex-1 space-y-2">
                <input type="text" name="nama" placeholder="Nama Anda" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-medium focus:ring-2 focus:ring-[#00a6eb]/10 outline-none transition-all">
                <div class="flex items-start gap-2">
                    <textarea name="komentar" placeholder="Tulis komentar..." required class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-medium focus:ring-2 focus:ring-[#00a6eb]/10 outline-none transition-all resize-none" rows="2"></textarea>
                    <button type="submit" class="h-10 w-10 bg-[#00a6eb] text-white rounded-xl flex items-center justify-center hover:bg-[#0090d0] transition-colors shrink-0 active:scale-95">
                        <i class="bi bi-send-fill text-sm"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Comment List -->
        <div class="space-y-4 pt-2">
            @forelse($berita->komentar ?? [] as $komentar)
            <div class="flex gap-3">
                <div class="h-8 w-8 rounded-lg bg-blue-50 text-[#00a6eb] shrink-0 flex items-center justify-center font-bold text-[10px] uppercase">
                    {{ substr($komentar->nama, 0, 2) }}
                </div>
                <div class="flex-1 bg-slate-50 p-3 rounded-2xl rounded-tl-none">
                    <div class="flex items-center justify-between mb-1">
                        <h5 class="text-[10px] font-bold text-slate-800">{{ $komentar->nama }}</h5>
                        <span class="text-[8px] text-slate-400">{{ \Carbon\Carbon::parse($komentar->created_at)->diffForHumans() }}</span>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-relaxed">{{ $komentar->komentar }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-slate-400">
                <i class="bi bi-chat-dots text-2xl mb-2 block text-slate-300"></i>
                <p class="text-xs font-medium">Belum ada komentar.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="h-px w-full bg-slate-100"></div>

    <!-- Recent Posts -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4">Berita Lainnya</h3>
        <div class="space-y-3">
            @foreach($recent_berita as $recent)
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
                        <h4 class="text-xs font-bold text-slate-800 leading-snug group-hover:text-[#00a6eb] transition-colors line-clamp-1">{{ $recent->judul_berita }}</h4>
                        <p class="text-[9px] text-slate-500 line-clamp-2 leading-relaxed mt-1">{{ strip_tags(Str::limit($recent->isi_berita, 100)) }}</p>
                        <p class="text-[9px] text-slate-400 mt-1">{{ \Carbon\Carbon::parse($recent->tanggal_berita)->translatedFormat('d F Y') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
