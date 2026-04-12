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
        <h1 class="text-2xl font-black text-white tracking-tight relative z-10">Tentang Desa</h1>
        <p class="text-xs text-white/70 mt-1 relative z-10">{{ $village['name'] ?? 'Desa Adat' }}</p>
    </div>

    <div class="px-5 -mt-4 space-y-4 relative z-10">
        <!-- Stats -->
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm">
                <p class="text-[9px] text-slate-400 uppercase font-bold">Banjar</p>
                <p class="text-lg font-black text-slate-800">{{ $totalBanjar }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm">
                <p class="text-[9px] text-slate-400 uppercase font-bold">Pura</p>
                <p class="text-lg font-black text-slate-800">{{ $totalPura }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm">
                <p class="text-[9px] text-slate-400 uppercase font-bold">Krama Tamiu</p>
                <p class="text-lg font-black text-slate-800">{{ $totalKramaTamiu }}</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm">
                <p class="text-[9px] text-slate-400 uppercase font-bold">Unit Usaha</p>
                <p class="text-lg font-black text-slate-800">{{ $totalUsaha }}</p>
            </div>
        </div>

        <!-- Daftar Banjar -->
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-2">Daftar Banjar</h3>
            <div class="space-y-2">
                @forelse($banjar as $b)
                <div class="bg-white rounded-xl border border-slate-100 p-3.5">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 bg-slate-50 rounded-lg flex items-center justify-center shrink-0 border border-slate-100">
                            <i class="bi bi-house-door text-slate-400 text-sm"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-800">{{ $b->nama_banjar }}</p>
                    </div>
                </div>
                @empty
                <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
                    <p class="text-xs text-slate-400">Belum ada data banjar</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Daftar Pura -->
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-2">Daftar Pura</h3>
            <div class="space-y-2">
                @forelse($pura as $item)
                <div class="bg-white rounded-xl border border-slate-100 p-3.5">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 bg-slate-50 rounded-lg flex items-center justify-center shrink-0 border border-slate-100">
                            <i class="bi bi-building text-slate-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800">{{ $item->nama_pura }}</p>
                            <p class="text-[10px] text-slate-400">
                                <i class="bi bi-geo-alt"></i> Banjar {{ $item->nama_banjar ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
                    <p class="text-xs text-slate-400">Belum ada data pura</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
