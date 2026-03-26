@extends('mobile_layout')

@section('content')
<!-- Header Area (Hero Banner) -->
<div class="bg-linear-to-br from-primary-dark to-primary-light h-[260px] w-full rounded-b-4xl relative shadow-lg px-5 pt-8 overflow-hidden">
    <!-- Abstract background decorations -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-1/3 -translate-y-1/3"></div>
    <div class="absolute bottom-10 left-0 w-24 h-24 bg-blue-400/20 rounded-full blur-xl transform -translate-x-1/2"></div>

    <div class="flex items-center justify-between z-10 relative">
        <div class="flex items-center gap-3">
            <div class="h-11 w-11 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/30 text-white font-black text-lg shadow-inner">
                {{ substr(Session::get('namapt'), 0, 1) }}
            </div>
            <div>
                <p class="text-white/70 text-[9px] uppercase tracking-widest font-black mb-0.5">Selamat Datang,</p>
                <h2 class="text-white text-[15px] font-bold leading-tight">{{ Session::get('namapt') }}</h2>
                <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-400/20 text-yellow-300 rounded text-[9px] font-bold tracking-wide border border-yellow-400/30">
                    {{ Session::get('status') }}
                </span>
            </div>
        </div>
        <button class="h-10 w-10 bg-white/10 hover:bg-white/20 transition backdrop-blur-sm rounded-full flex items-center justify-center text-white border border-white/20 shadow-sm relative">
            <span class="absolute top-2 right-2.5 h-2 w-2 bg-red-500 rounded-full border border-primary-dark"></span>
            <i class="bi bi-bell"></i>
        </button>
    </div>

    <!-- Floating Stats Card -->
    <div class="absolute -bottom-16 left-5 right-5 bg-white rounded-3xl shadow-[0_10px_30px_-10px_rgba(4,72,139,0.15)] border border-slate-100/60 p-5 transform transition-transform hover:-translate-y-1 z-20">
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 text-center">Rekap Kontribusi BPD</p>
        <h3 class="text-2xl font-black text-primary-dark text-center tracking-tight mb-4">Rp {{ number_format($totalpunia ?? 0, 0, ',', '.') }}</h3>
        
        <div class="flex justify-between items-center border-t border-slate-100 pt-3">
            <div class="text-center w-full border-r border-slate-100">
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wide">Unit Usaha</p>
                <p class="text-base font-black text-slate-700">{{ isset($usaha) ? count($usaha) : 0 }} <span class="text-[9px] font-bold text-slate-400">Unit</span></p>
            </div>
            <div class="text-center w-full">
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wide">Tenaga Kerja</p>
                <p class="text-base font-black text-slate-700">{{ $jml_karyawan ?? 0 }} <span class="text-[9px] font-bold text-slate-400">Orang</span></p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="px-5 pt-20 pb-8 space-y-7">
    
    <!-- Quick Actions (Menu Grid - KitaBisa Style) -->
    <div>
        <h4 class="text-[11px] font-black text-slate-800 mb-3 px-1 uppercase tracking-wider">Layanan Tersedia</h4>
        <div class="grid grid-cols-4 gap-x-2 gap-y-4">
            
            <!-- Item: Bayar Punia -->
            <a href="{{ url('administrator/datapunia_wajib') }}" class="flex flex-col items-center gap-2 group">
                <div class="h-[52px] w-[52px] bg-linear-to-br from-emerald-50 to-emerald-100/50 text-emerald-600 rounded-2xl flex items-center justify-center text-[22px] group-hover:scale-[1.03] transition-transform shadow-sm border border-emerald-200/60">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="text-[9px] font-bold text-center text-slate-600 leading-[1.2]">Bayar<br>Punia</span>
            </a>
            
            <!-- Item: Daftar Loker -->
            <a href="{{ url('administrator/data_tenagakerja') }}" class="flex flex-col items-center gap-2 group">
                <div class="h-[52px] w-[52px] bg-linear-to-br from-sky-50 to-sky-100/50 text-sky-600 rounded-2xl flex items-center justify-center text-[22px] group-hover:scale-[1.03] transition-transform shadow-sm border border-sky-200/60">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <span class="text-[9px] font-bold text-center text-slate-600 leading-[1.2]">Daftar<br>Loker</span>
            </a>
            
            <!-- Item: Data Usaha -->
            <a href="{{ url('administrator/data_usaha') }}" class="flex flex-col items-center gap-2 group">
                <div class="h-[52px] w-[52px] bg-linear-to-br from-amber-50 to-amber-100/50 text-amber-600 rounded-2xl flex items-center justify-center text-[22px] group-hover:scale-[1.03] transition-transform shadow-sm border border-amber-200/60">
                    <i class="bi bi-shop-window"></i>
                </div>
                <span class="text-[9px] font-bold text-center text-slate-600 leading-[1.2]">Data<br>Usaha</span>
            </a>
            
            <!-- Item: Profil Akun -->
            <a href="{{ url('administrator/userprofile') }}" class="flex flex-col items-center gap-2 group">
                <div class="h-[52px] w-[52px] bg-linear-to-br from-purple-50 to-purple-100/50 text-purple-600 rounded-2xl flex items-center justify-center text-[22px] group-hover:scale-[1.03] transition-transform shadow-sm border border-purple-200/60">
                    <i class="bi bi-person-gear"></i>
                </div>
                <span class="text-[9px] font-bold text-center text-slate-600 leading-[1.2]">Profil<br>Akun</span>
            </a>

            <!-- Item: Arsip Laporan -->
            <a href="{{ url('administrator/data_laporan') }}" class="flex flex-col items-center gap-2 group">
                <div class="h-[52px] w-[52px] bg-linear-to-br from-indigo-50 to-indigo-100/50 text-indigo-600 rounded-2xl flex items-center justify-center text-[22px] group-hover:scale-[1.03] transition-transform shadow-sm border border-indigo-200/60">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <span class="text-[9px] font-bold text-center text-slate-600 leading-[1.2]">Arsip<br>Laporan</span>
            </a>

            <!-- Item: Panduan -->
            <a href="#" class="flex flex-col items-center gap-2 group">
                <div class="h-[52px] w-[52px] bg-linear-to-br from-rose-50 to-rose-100/50 text-rose-600 rounded-2xl flex items-center justify-center text-[22px] group-hover:scale-[1.03] transition-transform shadow-sm border border-rose-200/60">
                    <i class="bi bi-journal-check"></i>
                </div>
                <span class="text-[9px] font-bold text-center text-slate-600 leading-[1.2]">Pusat<br>Panduan</span>
            </a>
            
        </div>
    </div>

    <!-- Promo Banner Carousel -->
    <div class="bg-linear-to-r from-primary-dark to-[#0a65be] rounded-2xl p-4 shadow-md relative overflow-hidden flex items-center h-28">
        <div class="absolute -right-4 -bottom-4 opacity-20">
            <i class="bi bi-megaphone-fill text-8xl text-white"></i>
        </div>
        <div class="relative z-10 pr-12">
            <span class="px-2 py-0.5 bg-white/20 backdrop-blur-md rounded text-[7px] font-black text-white uppercase tracking-widest mb-1.5 inline-block">Sistem Terbaru</span>
            <h3 class="text-white font-black text-sm leading-tight mb-1">Akses Lebih Cepat & Mudah</h3>
            <p class="text-white/80 text-[9px] leading-snug font-medium mb-2 line-clamp-2">Sekarang Anda dapat mengirim loker dan membayar iuran rutin langsung dari genggaman Anda.</p>
        </div>
    </div>
    
    <!-- Recent Activity / News List -->
    <div>
        <div class="flex items-center justify-between px-1 mb-3">
            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-wider">Aktivitas Terkini</h4>
            <a href="#" class="text-[9px] font-bold text-primary-dark bg-blue-50 px-2 py-1 rounded-md">Lihat Semua</a>
        </div>
        
        <div class="space-y-2.5">
            <!-- Feed Item 1 -->
            <div class="bg-white border border-slate-100 rounded-xl p-3 flex items-start gap-3 shadow-sm hover:shadow-md transition-shadow">
                <div class="h-10 w-10 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 shrink-0 mt-0.5">
                    <i class="bi bi-clock-history text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-0.5">
                        <h5 class="text-xs font-bold text-slate-800 leading-tight">Sistem Mobile Dirilis</h5>
                        <span class="text-[8px] font-bold text-slate-400">Baru saja</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium leading-snug">Tampilan baru khusus pengguna lapangan (Kelian Banjar & Unit Usaha) telah aktif.</p>
                </div>
            </div>
            
            <!-- Feed Item 2 -->
             <div class="bg-white border border-slate-100 rounded-xl p-3 flex items-start gap-3 shadow-sm hover:shadow-md transition-shadow">
                <div class="h-10 w-10 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center text-emerald-500 shrink-0 mt-0.5">
                    <i class="bi bi-check-circle text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-0.5">
                        <h5 class="text-xs font-bold text-slate-800 leading-tight">Pembayaran Punia Terakhir</h5>
                        <span class="text-[8px] font-bold text-slate-400">1 Hari lalu</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium leading-snug">Pastikan pembayaran kewajiban bulan ini sudah tercatat di sistem pusat.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
