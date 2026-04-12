@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-12 pb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <a href="{{ route('public.home') }}" class="inline-flex items-center gap-1.5 text-white/70 hover:text-white transition-colors mb-3">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Beranda</span>
        </a>
        <h1 class="text-2xl font-black text-white tracking-tight relative z-10">Pura</h1>
        <p class="text-xs text-white/70 mt-1 relative z-10">Daftar pura dan donasi punia pura</p>
    </div>

    <div class="px-5 -mt-4 space-y-4 relative z-10" x-data="{ search: '' }">
        <!-- Search -->
        <div class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" x-model="search"
                   class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10"
                   placeholder="Cari nama pura...">
        </div>

        <!-- Pura List -->
        @forelse($pura as $item)
        <a href="{{ route('public.pura.detail', $item->id_pura) }}" 
           class="block bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow"
           x-show="!search || '{{ strtolower($item->nama_pura) }}'.includes(search.toLowerCase())">
            <div class="flex gap-3">
                <!-- Image Thumbnail -->
                <div class="w-24 h-24 flex-shrink-0 bg-slate-50">
                    @if($item->gambar_pura)
                    <img src="{{ asset($item->gambar_pura) }}" class="w-full h-full object-cover" alt="{{ $item->nama_pura }}" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'bi bi-building text-slate-300 text-2xl\'></i></div>'">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-building text-slate-300 text-2xl"></i>
                    </div>
                    @endif
                </div>
                <!-- Info -->
                <div class="flex-1 py-3 pr-3">
                    <h3 class="text-sm font-black text-slate-800 leading-tight">{{ $item->nama_pura }}</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        <i class="bi bi-geo-alt"></i> Banjar {{ $item->nama_banjar ?? '-' }}
                    </p>
                    @if($item->wuku_odalan)
                    <p class="text-[10px] text-[#00a6eb] mt-0.5">
                        <i class="bi bi-calendar-event"></i> Odalan: Wuku {{ $item->wuku_odalan }}
                    </p>
                    @endif
                    <div class="mt-1.5">
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">
                            Punia: Rp {{ number_format($item->total_punia, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="bg-white rounded-2xl border border-slate-100 border-dashed p-8 text-center">
            <i class="bi bi-building text-4xl text-slate-200 mb-2"></i>
            <p class="text-sm text-slate-400">Belum ada data pura</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
