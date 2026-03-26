@extends('mobile_layout')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Halo,</h1>
            <p class="text-slate-500 text-sm font-medium">{{ Session::get('namapt') }}</p>
        </div>
        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center border border-slate-200 shadow-sm overflow-hidden">
             @php
                $logoPath = 'storage/logos/logo.png';
                if (!file_exists(public_path($logoPath))) {
                    $logoPath = 'storage/login_bg/donasi.png';
                }
            @endphp
            <img src="{{ asset($logoPath) }}" class="w-8 h-8 object-contain" alt="Logo">
        </div>
    </div>

    <!-- Stats Card (Minimalist) -->
    <div class="bg-[#00a6eb] rounded-3xl p-6 text-white mb-8 shadow-lg shadow-[#00a6eb]/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-xs font-bold text-white/80 uppercase tracking-widest mb-1">Status Iuran Bulanan</p>
        <h2 class="text-2xl font-black mb-4">Lancar</h2>
        <div class="flex items-center gap-2 text-[10px] font-bold bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full w-fit">
            <i class="bi bi-calendar-check"></i>
            Terakhir bayar: {{ date('F Y') }}
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-2 gap-4 mb-10">
        <a href="{{ url('administrator/usaha/iuran') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                <i class="bi bi-wallet2 text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-1">Bayar Iuran</h3>
            <p class="text-slate-400 text-[10px] leading-tight">Lakukan iuran bulanan desa</p>
        </a>
        <a href="{{ url('administrator/usaha/loker') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                <i class="bi bi-megaphone text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-1">Cari Karyawan</h3>
            <p class="text-slate-400 text-[10px] leading-tight">Posting lowongan kerja baru</p>
        </a>
    </div>

    <!-- Recent Activity / News Feed -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-black text-slate-800 tracking-tight">Info Desa Terbaru</h3>
            <a href="#" class="text-[#00a6eb] text-xs font-bold">Lihat Semua</a>
        </div>
        <div class="space-y-4">
             <div class="flex gap-4 bg-slate-50 p-4 rounded-3xl border border-slate-100">
                <div class="w-20 h-20 bg-slate-200 rounded-2xl shrink-0 overflow-hidden">
                    <img src="{{ asset('storage/login_bg/donasi.png') }}" class="w-full h-full object-cover grayscale opacity-50" alt="News">
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-[9px] font-black text-[#00a6eb] uppercase tracking-widest mb-1">Pengumuman</span>
                    <h4 class="text-xs font-bold text-slate-800 leading-snug mb-2">Rapat Koordinasi Bulanan Unit Usaha Banjar</h4>
                    <p class="text-[10px] text-slate-400 font-medium">26 Maret 2026</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
