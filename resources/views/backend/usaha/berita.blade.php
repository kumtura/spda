@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Berita Desa</h2>
        <p class="text-[10px] text-slate-400 mt-1">Informasi terkini dari desa adat</p>
    </div>

    <!-- Berita List -->
    <div class="space-y-3">
        @forelse($berita as $news)
            <a href="{{ url('administrator/usaha/berita/detail/'.$news->id_berita) }}" class="block bg-white rounded-xl border border-slate-100 overflow-hidden hover:shadow-md transition-all group">
                <div class="flex gap-0">
                    <div class="w-[120px] shrink-0 bg-slate-50 flex items-center justify-center relative overflow-hidden">
                        @if($news->foto)
                            <img src="{{ asset('storage/berita/foto/'.$news->foto) }}" class="h-full w-full object-cover" alt="{{ $news->judul }}">
                        @else
                            <i class="bi bi-newspaper text-3xl text-slate-200"></i>
                        @endif
                    </div>
                    <div class="flex-1 p-3.5 flex flex-col justify-center min-w-0">
                        @if($news->kategori)
                        <span class="text-[#00a6eb] text-[8px] font-bold uppercase tracking-wider mb-1">{{ $news->kategori->nama_kategori }}</span>
                        @endif
                        <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 line-clamp-2 group-hover:text-[#00a6eb] transition-colors">{{ $news->judul }}</h4>
                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="py-10 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <i class="bi bi-newspaper text-3xl text-slate-200 mb-2 block"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada berita</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($berita->hasPages())
    <div class="flex justify-center pt-4">
        {{ $berita->links() }}
    </div>
    @endif
</div>
@endsection
