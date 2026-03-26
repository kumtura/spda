@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-8">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Program Donasi</h2>
        <p class="text-[10px] text-slate-400 font-bold mt-1">Dukung pembangunan desa melalui donasi terpercaya.</p>
    </div>

    <!-- Total Stats -->
    <div class="bg-blue-50 rounded-2xl p-5 border border-blue-100 flex items-center justify-between">
        <div>
            <p class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-widest mb-1">Total Terkumpul</p>
            <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($total_sumbangan, 0, ',', '.') }}</h3>
        </div>
        <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-[#00a6eb] border border-blue-100 shadow-sm">
            <i class="bi bi-gift-fill"></i>
        </div>
    </div>

    <!-- Program Cards -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4">Program Aktif</h3>
        <div class="flex gap-4 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
            <div class="w-[260px] shrink-0 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="h-28 bg-slate-50 relative flex items-center justify-center">
                    <i class="bi bi-image text-3xl text-slate-200"></i>
                    <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-full uppercase">Pembangunan</span>
                </div>
                <div class="p-4">
                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2">Sarana & Prasarana Pura Desa</h4>
                    <p class="text-[9px] text-slate-400 font-medium">Terkumpul</p>
                    <p class="text-xs font-black text-[#00a6eb]">Rp 15.000.000</p>
                </div>
            </div>
            <div class="w-[260px] shrink-0 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="h-28 bg-slate-50 relative flex items-center justify-center">
                    <i class="bi bi-image text-3xl text-slate-200"></i>
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-full uppercase">Mendesak</span>
                </div>
                <div class="p-4">
                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2">Bantuan Bencana Alam Lokal</h4>
                    <p class="text-[9px] text-slate-400 font-medium">Terkumpul</p>
                    <p class="text-xs font-black text-[#00a6eb]">Rp 4.500.000</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontribusi Terkini -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4">Kontribusi Terkini</h3>
        <div class="space-y-3">
            @foreach($sumbangan as $item)
                <div class="bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                    <div class="h-10 w-10 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center shrink-0 overflow-hidden">
                        @if($item->profile)
                            <img src="{{ asset('sumbangan/thumbnail/'.$item->profile) }}" class="h-full w-full object-cover" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <i class="bi bi-person text-slate-300" style="display:none;"></i>
                        @else
                            <i class="bi bi-person text-slate-300"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $item->nama ?: 'Donatur Anonim' }}</p>
                        <p class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#00a6eb]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
