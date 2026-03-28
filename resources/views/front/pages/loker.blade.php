@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Lowongan Kerja</h2>
        <p class="text-[10px] text-slate-400 font-medium mt-1">Temukan peluang karir di unit usaha SPDA</p>
    </div>

    <!-- Filter Kategori -->
    @if($kategori_list->count() > 0)
    <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
        <a href="{{ route('public.loker', ['kategori' => 'all']) }}"
           class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all {{ $kategori_filter === 'all' ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-200/50' : 'bg-white text-slate-500 border-slate-200 hover:border-[#00a6eb]/30' }}">
            Semua
        </a>
        @foreach($kategori_list as $kat)
        <a href="{{ route('public.loker', ['kategori' => $kat->id_kategori_usaha]) }}"
           class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all {{ $kategori_filter == $kat->id_kategori_usaha ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-200/50' : 'bg-white text-slate-500 border-slate-200 hover:border-[#00a6eb]/30' }}">
            {{ $kat->nama_kategori_usaha }}
        </a>
        @endforeach
    </div>
    @endif

    <!-- Loker List -->
    <div class="space-y-3">
        @forelse($lokers as $loker)
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
        @empty
            <div class="py-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <i class="bi bi-briefcase text-3xl text-slate-300 mb-2 block"></i>
                <p class="text-xs font-medium text-slate-500">Belum ada lowongan kerja tersedia.</p>
                <p class="text-[10px] text-slate-400 mt-1">Coba filter kategori lain atau kembali lagi nanti.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($lokers->hasPages())
    <div class="flex justify-center pt-4">
        {{ $lokers->links() }}
    </div>
    @endif
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
