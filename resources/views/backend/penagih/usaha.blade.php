@extends('mobile_layout')

@section('isi_menu')
@php
    $totalUsaha = count($usahaWithPayment);
    $sudahBayar = collect($usahaWithPayment)->filter(fn($u) => $u['payment'])->count();
    $belumBayar = $totalUsaha - $sudahBayar;
@endphp

<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ 
    searchQuery: '',
    showFilter: false
}">
    <!-- Back + Header -->
    <div>
        <a href="{{ url('administrator/penagih') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>

        @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle text-blue-600 text-sm"></i>
                <p class="text-xs text-blue-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Unit Usaha</h1>
                <p class="text-slate-400 text-[10px] mt-1">Punia usaha - Banjar {{ $banjar ? $banjar->nama_banjar : '-' }}</p>
            </div>
            <button @click="showFilter = !showFilter" class="h-9 w-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400">
                <i class="bi bi-filter text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div x-show="showFilter" x-transition class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <form action="{{ url('administrator/penagih/usaha') }}" method="GET" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Bulan</label>
                <select name="bulan" class="w-full text-xs border border-slate-200 rounded-lg p-2">
                    @foreach($months as $k => $v)
                    <option value="{{ $k }}" {{ $selectedMonth == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Tahun</label>
                <select name="tahun" class="w-full text-xs border border-slate-200 rounded-lg p-2">
                    @for($y = date('Y'); $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-[#00a6eb] text-white px-4 py-2 rounded-lg text-xs font-bold">Filter</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <p class="text-[9px] uppercase text-white/60 tracking-widest mb-1">Periode {{ $months[$selectedMonth] }} {{ $selectedYear }}</p>
            <h3 class="text-2xl font-black mb-3">{{ $totalUsaha }} Unit</h3>
            <div class="flex gap-4 text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Sudah Bayar</p>
                    <p class="font-bold text-emerald-300">{{ $sudahBayar }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Belum Bayar</p>
                    <p class="font-bold text-rose-300">{{ $belumBayar }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div>
        <div class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" x-model="searchQuery"
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                   placeholder="Cari nama usaha...">
        </div>
    </div>

    <!-- Usaha List -->
    <div class="space-y-2">
        @forelse($usahaWithPayment as $item)
        @php
            $u = $item['usaha'];
            $payment = $item['payment'];
        @endphp
        <a href="{{ url('administrator/penagih/usaha/detail/'.$u->id_usaha) }}" 
           class="block bg-white border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors"
           x-show="!searchQuery || '{{ strtolower($u->nama_usaha) }}'.includes(searchQuery.toLowerCase())">
            <div class="flex items-center gap-3 px-3 py-3">
                <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                    @if($u->logo)
                    @php
                        $logoPath = file_exists(public_path('usaha/icon/'.$u->logo)) 
                            ? 'usaha/icon/'.$u->logo 
                            : 'storage/usaha/icon/'.$u->logo;
                    @endphp
                    <img src="{{ asset($logoPath) }}" class="w-full h-full object-contain" alt="Logo" onerror="this.outerHTML='<i class=\'bi bi-building text-slate-300\'></i>'">
                    @else
                    <i class="bi bi-building text-slate-300"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ $u->nama_usaha }}</p>
                    <p class="text-[10px] text-slate-400">{{ $u->nama_kategori_usaha ?? 'Umum' }}</p>
                </div>
                <div class="shrink-0">
                    @if($payment)
                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                        <i class="bi bi-check-circle"></i> Lunas
                    </span>
                    @else
                    <span class="text-[9px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">
                        Belum
                    </span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada unit usaha di banjar ini</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
