@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Berita & Info</h2>
        <p class="text-[10px] text-slate-400 font-bold mt-1">Informasi terbaru seputar desa adat.</p>
    </div>

    @if(count($berita) > 0)
        <div class="space-y-4">
            @foreach($berita as $item)
                <a href="{{ route('public.berita.detail', $item->id_berita) }}" class="block group">
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="h-40 bg-slate-50 relative overflow-hidden">
                            @if($item->foto)
                                <img src="{{ asset('berita/'.$item->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $item->judul_berita }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full items-center justify-center bg-slate-50 absolute inset-0" style="display:none;">
                                    <i class="bi bi-image text-3xl text-slate-200"></i>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="bi bi-image text-3xl text-slate-200"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-[9px] text-[#00a6eb] font-bold uppercase tracking-wider mb-1">{{ \Carbon\Carbon::parse($item->tanggal_berita)->translatedFormat('d F Y') }}</p>
                            <h3 class="text-sm font-bold text-slate-800 leading-snug group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $item->judul_berita }}</h3>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-50">
                                <span class="text-[9px] text-slate-400 font-medium">Baca selengkapnya</span>
                                <i class="bi bi-chevron-right text-[10px] text-slate-300 group-hover:text-[#00a6eb] transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $berita->links() }}
        </div>
    @else
        <div class="py-16 text-center">
            <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="bi bi-journal-x text-2xl text-slate-300"></i>
            </div>
            <p class="text-xs font-bold text-slate-400">Belum ada berita yang diterbitkan.</p>
        </div>
    @endif
</div>
@endsection
