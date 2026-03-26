@extends('mobile_layout')

@section('isi_menu')
<div class="px-6 py-4 space-y-8" x-data="{ 
    stats: {
        usaha: {{ count($usaha) }},
        tenaga: {{ $jml_karyawan }},
        punia: '{{ format_rupiah($totalpunia) }}'
    }
}">
    <!-- Welcome Text -->
    <div>
        <h1 class="text-2xl font-black tracking-tight text-slate-800 leading-none mb-1">Rahajeng,</h1>
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">{{ Session::get('namapt') }} <span class="text-[#00a6eb]">| Kelian Adat</span></p>
    </div>

    <!-- Status Banjar Card -->
    <div class="bg-[#00a6eb] rounded-3xl p-6 text-white shadow-lg shadow-[#00a6eb]/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-xs font-bold text-white/80 uppercase tracking-widest mb-1">Otoritas Wilayah</p>
        <h2 class="text-2xl font-black mb-4">Banjar {{ Auth::user()->banjar->nama_banjar ?? 'Adat' }}</h2>
        <div class="flex items-center gap-4">
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Total Usaha</p>
                <p class="text-xl font-black" x-text="stats.usaha"></p>
            </div>
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Tenaga Lokal</p>
                <p class="text-xl font-black" x-text="stats.tenaga"></p>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url('administrator/data_usaha') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-building-add text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Daftar Usaha</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Input Hotel/Usaha Baru</p>
            </a>

            <a href="{{ url('administrator/datauser') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-person-lock text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Kelola Akses</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Admin Unit Usaha</p>
            </a>

            <a href="{{ url('administrator/datapunia_wajib') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                    <i class="bi bi-wallet2 text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Cek Iuran</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Monitor Punia Wajib</p>
            </a>

            <a href="{{ url('administrator/data_tenagakerja_interview') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                    <i class="bi bi-person-workspace text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Penerimaan</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Transparansi Loker</p>
            </a>
        </div>
    </div>

    <!-- News Feed (Replaces Fokus Kelian) -->
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
                    <h4 class="text-xs font-bold text-slate-800 leading-snug mb-2">Koordinasi Banjar: Optimalisasi Penyerapan Tenaga Kerja Lokal</h4>
                    <p class="text-[10px] text-slate-400 font-medium">{{ date('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
