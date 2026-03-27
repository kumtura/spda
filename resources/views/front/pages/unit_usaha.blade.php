@extends('mobile_layout_public')

@section('content')
<div class="px-4 pt-8 pb-10 min-h-screen bg-white">
    <div class="flex items-center justify-between mb-6 pt-16">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Unit Usaha</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $village['name'] ?? 'SPDA' }}</p>
        </div>
        <a href="{{ route('public.register_usaha') }}" class="text-[9px] font-bold text-white uppercase tracking-widest bg-[#00a6eb] px-3 py-1.5 rounded-full hover:bg-blue-600 transition-colors shadow-[0_2px_10px_rgba(0,166,235,0.3)]"><i class="bi bi-plus-lg mr-1"></i>Daftar Usaha</a>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($usaha as $u)
        <div class="bg-white rounded-2xl p-4 shadow-[0_4px_15px_-5px_rgba(0,0,0,0.05)] border border-slate-100/50 flex flex-col gap-3 group hover:border-[#00a6eb]/30 transition-colors">
            <div class="flex gap-4">
                <div class="h-14 w-14 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden shrink-0 flex items-center justify-center p-1">
                    @if($u->foto)
                        <img src="{{ asset('logousaha/'.$u->foto) }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        <i class="bi bi-shop text-xl text-slate-300"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-black text-xs text-slate-800 uppercase tracking-tight">{{ $u->nama_usaha }}</h3>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="bg-emerald-50 text-emerald-600 text-[8px] font-bold px-1.5 py-0.5 rounded uppercase tracking-widest border border-emerald-100">
                            @if($u->aktif_status == '1')
                                <i class="bi bi-check-circle-fill mr-0.5"></i> Terverifikasi
                            @else
                                <i class="bi bi-clock-fill text-amber-500 mr-0.5"></i> Pending
                            @endif
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1.5 leading-snug wrap-break-word max-w-full">
                        <i class="bi bi-geo-alt-fill text-slate-300 mr-0.5"></i> {{ $u->alamat ?? 'Wilayah SPDA' }}
                    </p>
                </div>
            </div>
            
            <div class="h-px w-full bg-slate-50"></div>
            
            @php
                // Hitung total kontribusi untuk unit usaha ini
                $kontribusi = \App\Models\Danapunia::where('id_usaha', $u->id_usaha)->where('aktif', '1')->sum('jumlah_dana');
            @endphp
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-full bg-blue-50 flex items-center justify-center border border-blue-100">
                        <i class="bi bi-wallet2 text-[#00a6eb] text-[10px]"></i>
                    </div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Total Kontribusi</span>
                </div>
                <span class="text-xs font-black text-[#00a6eb] bg-blue-50/50 px-2.5 py-1 rounded-md border border-[#00a6eb]/10">Rp {{ number_format($kontribusi, 0, ',', '.') }}</span>
            </div>
        </div>
        @empty
        <div class="bg-slate-50 rounded-2xl border border-slate-100 border-dashed p-8 text-center col-span-1">
            <i class="bi bi-shop text-4xl text-slate-200 mb-3 block"></i>
            <p class="text-xs font-bold text-slate-500 tracking-wide">Belum ada unit usaha.</p>
            <p class="text-[10px] text-slate-400 mt-1">Daftarkan usaha Anda sekarang.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-8 pagination-wrapper">
        {{ $usaha->links('pagination::tailwind') }}
    </div>
</div>

<style>
/* Custom Tailwind Pagination Styles Override For Mobile */
.pagination-wrapper nav {
    @apply flex justify-center items-center flex-wrap gap-1;
}
.pagination-wrapper span, .pagination-wrapper a {
    @apply text-[10px] px-2 py-1;
}
</style>
@endsection
