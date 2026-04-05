@extends('mobile_layout')

@section('isi_menu')
<div class="px-6 py-4 space-y-6">
    <!-- Welcome Text -->
    <div>
        <h1 class="text-2xl font-black tracking-tight text-slate-800 leading-none mb-1">Rahajeng,</h1>
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">{{ Session::get('namapt') }} <span class="text-[#00a6eb]">| Ticket Counter</span></p>
    </div>

    <!-- Shift Status Card -->
    @if($activeShift)
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl p-5 text-white shadow-lg shadow-emerald-500/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <div class="h-2.5 w-2.5 bg-white rounded-full animate-pulse"></div>
                <span class="text-[9px] font-bold uppercase tracking-widest text-white/80">Shift Aktif</span>
            </div>
            <span class="text-[9px] font-bold bg-white/20 px-2 py-0.5 rounded-full">
                Sejak {{ $activeShift->waktu_masuk->format('H:i') }}
            </span>
        </div>
        <h2 class="text-lg font-black mb-1">{{ $activeShift->objekWisata->nama_objek }}</h2>
        <p class="text-xs text-white/70">Durasi: {{ $activeShift->waktu_masuk->diffForHumans(now(), true) }}</p>
        <div class="flex items-center gap-4 mt-3">
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Penjualan Shift</p>
                <p class="text-lg font-black">Rp {{ number_format($penjualanShift, 0, ',', '.') }}</p>
            </div>
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Tiket Shift</p>
                <p class="text-lg font-black">{{ $tiketShift }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-5 text-white shadow-lg shadow-orange-500/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <div class="flex items-center gap-2 mb-3">
            <i class="bi bi-exclamation-circle text-lg"></i>
            <span class="text-[9px] font-bold uppercase tracking-widest text-white/80">Belum Clock In</span>
        </div>
        <p class="text-sm font-bold mb-3">Anda belum memulai shift hari ini. Silakan absen terlebih dahulu.</p>
        <a href="{{ url('administrator/ticketcounter/absensi') }}" class="inline-block bg-white text-orange-600 text-xs font-black px-5 py-2.5 rounded-xl active:scale-95 transition-all">
            <i class="bi bi-clipboard-check mr-1"></i> Clock In Sekarang
        </a>
    </div>
    @endif

    <!-- Today Stats -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-3xl p-5 text-white shadow-lg shadow-[#00a6eb]/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Statistik Hari Ini — {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <h3 class="text-2xl font-black mb-3">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</h3>
        <div class="flex items-center gap-4">
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Tiket Terjual</p>
                <p class="text-xl font-black">{{ $totalTiketTerjual }}</p>
            </div>
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Transaksi</p>
                <p class="text-xl font-black">{{ $tiketHariIni->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div>
        <h3 class="font-black text-slate-800 tracking-tight mb-3">Menu</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url('administrator/ticketcounter/absensi') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-clipboard-check text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Absensi</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Clock In / Clock Out</p>
            </a>

            <a href="{{ url('administrator/ticketcounter/tiket/jual') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-cash-coin text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Jual Tiket</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Penjualan Offline</p>
            </a>

            <a href="{{ url('administrator/ticketcounter/tiket/scan') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-qr-code-scan text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Scan Tiket</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Validasi QR Code</p>
            </a>

            <a href="{{ url('administrator/ticketcounter/tiket/transaksi') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-receipt text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Transaksi</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Pembayaran Masuk</p>
            </a>

            <a href="{{ url('administrator/ticketcounter/tiket/laporan') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-bar-chart-line text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Laporan</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Rekap Harian</p>
            </a>

            <a href="{{ url('administrator/ticketcounter/tiket') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-ticket-perforated text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Dashboard Tiket</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Overview Penjualan</p>
            </a>
        </div>
    </div>

    <!-- Assigned Locations -->
    @if($objekWisata->count() > 0)
    <div>
        <h3 class="font-black text-slate-800 tracking-tight mb-3">Lokasi Tugas</h3>
        <div class="space-y-3">
            @foreach($objekWisata as $objek)
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                        @if($objek->foto)
                            <img src="{{ asset('storage/wisata/'.$objek->foto) }}" class="h-full w-full object-cover" alt="{{ $objek->nama_objek }}">
                        @else
                            <i class="bi bi-geo-alt text-slate-300 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 truncate">{{ $objek->nama_objek }}</h4>
                        <p class="text-[10px] text-slate-400 truncate">{{ $objek->alamat }}</p>
                        @if($objek->jam_buka && $objek->jam_tutup)
                        <p class="text-[9px] text-[#00a6eb] font-bold mt-1">
                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($objek->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($objek->jam_tutup)->format('H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
