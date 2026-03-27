@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-8">
    <!-- Page Title (Simplified to match Donasi/Punia) -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Berita & Info</h2>
        <p class="text-[10px] text-slate-400 font-bold mt-1">Informasi terbaru seputar kegiatan dan pembangunan desa adat.</p>
    </div>

    <div class="space-y-8">
        @if(count($berita) > 0)
            @php $featured = $berita->first(); @endphp
            
            <!-- Featured Article -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Berita Terbaru</h3>
                </div>
                <a href="{{ route('public.berita.detail', $featured->id_berita) }}" class="block group">
                    <div class="bg-white rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 border border-slate-100 group-hover:border-[#00a6eb]/30 transition-all transform group-hover:-translate-y-1">
                        <div class="relative h-56 bg-slate-100 overflow-hidden">
                            @if($featured->foto)
                                <img src="{{ asset('storage/berita/foto/'.$featured->foto) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $featured->judul_berita }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-200">
                                    <i class="bi bi-image text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span class="bg-[#00a6eb] text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">{{ \Carbon\Carbon::parse($featured->tanggal_berita)->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-lg font-black text-slate-800 leading-tight group-hover:text-[#00a6eb] transition-colors line-clamp-2 mb-3">{{ $featured->judul_berita }}</h2>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">{{ strip_tags($featured->isi_berita) }}</p>
                            <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ \Carbon\Carbon::parse($featured->tanggal_berita)->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 ml-auto text-[#00a6eb]">
                                    <span>Selengkapnya</span>
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- News List -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kabar Desa</h3>
                </div>
                <div class="grid grid-cols-1 gap-8">
                    @foreach($berita->skip(1) as $item)
                        <a href="{{ route('public.berita.detail', $item->id_berita) }}" class="group block py-2">
                            <div class="flex gap-4">
                                <div class="h-24 w-24 bg-slate-50 rounded-2xl overflow-hidden shrink-0 shadow-sm border border-slate-100">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/berita/foto/'.$item->foto) }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $item->judul_berita }}">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-200">
                                            <i class="bi bi-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 pr-2 flex flex-col justify-between">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2 text-[8px] font-black uppercase tracking-[0.12em]">
                                            <span class="text-[#00a6eb]">{{ \Carbon\Carbon::parse($item->tanggal_berita)->translatedFormat('d M Y') }}</span>
                                            <span class="text-slate-200">•</span>
                                            <span class="text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_berita)->diffForHumans() }}</span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 group-hover:text-[#00a6eb] transition-colors">{{ $item->judul_berita }}</h4>
                                        <p class="text-[9px] text-slate-500 line-clamp-2 leading-relaxed">{{ strip_tags($item->isi_berita) }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 text-[8px] font-black text-[#00a6eb] uppercase tracking-widest mt-2">
                                        <span>Selengkapnya</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                {{ $berita->links('pagination::tailwind') }}
            </div>
        @else
            <div class="py-20 text-center bg-white rounded-[40px] border border-slate-100 shadow-sm">
                <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200 border border-slate-50">
                    <i class="bi bi-journal-x text-4xl"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum ada berita</h4>
                <p class="text-[10px] text-slate-400 mt-1 px-10">Kabar terbaru desa adat akan segera hadir di sini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
