@extends('mobile_layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen pb-20">
    <!-- Header Hero -->
    <div class="bg-white px-5 pt-10 pb-12 rounded-b-[40px] shadow-sm border-b border-slate-100">
        <div class="flex items-center justify-between mb-8">
            <div class="space-y-1">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Unit Usaha</h1>
                <div class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#00a6eb]"></span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kandungan Desa Adat</p>
                </div>
            </div>
            <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100/50">
                <i class="bi bi-shop text-[#00a6eb] text-xl"></i>
            </div>
        </div>

        <!-- Tentang Section Card -->
        <div class="bg-gradient-to-br from-[#00a6eb] to-[#0088cc] rounded-3xl p-6 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                <i class="bi bi-shield-heart text-[120px]"></i>
            </div>
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/10">
                    <i class="bi bi-info-circle text-[10px]"></i>
                    <span class="text-[9px] font-bold uppercase tracking-widest">Tentang Unit Usaha</span>
                </div>
                <h2 class="text-lg font-bold leading-tight">Membangun Kemandirian Ekonomi Desa Adat</h2>
                <p class="text-[11px] text-blue-50 leading-relaxed font-medium opacity-90">
                    Program Unit Usaha SPDA adalah wadah kolaborasi ekonomi lokal yang berkontribusi nyata dalam pembangunan dan kesejahteraan Desa Adat melalui partisipasi aktif pelaku usaha.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="px-5 -mt-6">
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-1">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Usaha</span>
                <p class="text-xl font-black text-slate-800 tracking-tight">{{ number_format($total_usaha, 0, ',', '.') }}</p>
                <div class="mt-1 flex items-center gap-1 text-[8px] font-bold text-emerald-500">
                    <i class="bi bi-check2-circle"></i> Terverifikasi
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-1">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Sinergi</span>
                <p class="text-xl font-black text-[#00a6eb] tracking-tight">Rp {{ number_format($total_kontribusi / 1000000, 1, ',', '.') }}M+</p>
                <div class="mt-1 flex items-center gap-1 text-[8px] font-bold text-[#00a6eb]">
                    <i class="bi bi-graph-up-arrow"></i> Kontribusi Sosial
                </div>
            </div>
        </div>
    </div>

    <!-- Main List -->
    <div class="px-5 mt-8 space-y-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Daftar Mitra Usaha</h3>
        </div>

        <!-- Filters -->
        <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
            <a href="{{ route('public.unit_usaha', ['banjar' => 'all']) }}" 
               class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all {{ ($selected_banjar == 'all' || !$selected_banjar) ? 'bg-[#00a6eb] text-white border-[#00a6eb]' : 'bg-white text-slate-500 border-slate-200' }}">
                Semua
            </a>
            @foreach($banjar_list as $b)
            <a href="{{ route('public.unit_usaha', ['banjar' => $b->id_data_banjar]) }}" 
               class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all {{ $selected_banjar == $b->id_data_banjar ? 'bg-[#00a6eb] text-white border-[#00a6eb]' : 'bg-white text-slate-500 border-slate-200' }}">
                {{ $b->nama_banjar }}
            </a>
            @endforeach
        </div>

        @forelse($usaha as $u)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:border-[#00a6eb]/30 transition-all group">
            <div class="flex gap-4">
                <div class="h-16 w-16 rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden shrink-0 flex items-center justify-center p-1 group-hover:shadow-md transition-shadow">
                    @if($u->logo)
                        @php
                            // Check if file exists in public/usaha/icon, otherwise use storage path
                            $logoPath = file_exists(public_path('usaha/icon/'.$u->logo)) 
                                ? 'usaha/icon/'.$u->logo 
                                : 'storage/usaha/icon/'.$u->logo;
                        @endphp
                        <img src="{{ asset($logoPath) }}" class="w-full h-full object-cover rounded-xl shadow-xs">
                    @else
                        <div class="h-full w-full flex flex-col items-center justify-center text-slate-200 gap-1">
                            <i class="bi bi-shop text-2xl"></i>
                            <span class="text-[7px] font-bold uppercase tracking-tighter">SPDA Mitra</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 py-0.5">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-black text-sm text-slate-800 leading-tight group-hover:text-[#00a6eb] transition-colors truncate">{{ $u->nama_usaha }}</h3>
                    </div>
                    <div class="mt-2 space-y-1.5">
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <i class="bi bi-geo-alt text-[10px]"></i>
                            <span class="text-[10px] font-medium">{{ $u->nama_banjar ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <i class="bi bi-people text-[10px]"></i>
                            <span class="text-[10px] font-medium">{{ $u->jumlah_tenaga_kerja }} tenaga kerja lokal</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <i class="bi bi-heart text-[10px]"></i>
                            <span class="text-[10px] font-medium">Kontribusi Rp {{ number_format($u->total_donasi, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <i class="bi bi-shop text-3xl text-slate-200 mb-2 block"></i>
            <p class="text-xs font-bold text-slate-400">Belum ada mitra usaha terdaftar.</p>
            <p class="text-[10px] text-slate-400 mt-1">Unit usaha yang terdaftar akan muncul di sini.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8 px-5 pb-10">
        {{ $usaha->links('pagination::tailwind') }}
    </div>
</div>

<style>
/* Custom Pagination Adjustments */
.pagination-wrapper nav {
    @apply flex justify-center !important;
}
</style>
@endsection
