@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10 flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/ticketcounter') }}" class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                <i class="bi bi-arrow-left text-white text-lg"></i>
            </a>
            <div>
                <h1 class="text-lg font-black">Laporan Harian</h1>
                <p class="text-[10px] text-white/80">Rekap penjualan dan aktivitas per hari</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-5">
        <!-- Date Filter -->
        <form method="GET" action="{{ url('administrator/ticketcounter/tiket/laporan') }}" class="flex gap-2">
            <input type="date" name="tanggal" value="{{ $filterDate }}" 
                class="flex-1 px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
            <button type="submit" class="px-5 py-2.5 bg-[#00a6eb] text-white text-xs font-bold rounded-lg active:scale-95 transition-all">
                Lihat
            </button>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-2">
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 text-center">
                <p class="text-[8px] font-bold text-emerald-600 uppercase tracking-widest mb-1">Offline</p>
                <p class="text-lg font-black text-emerald-700">{{ $totalTiketOffline }}</p>
                <p class="text-[9px] text-emerald-500 font-bold">tiket</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                <p class="text-[8px] font-bold text-blue-600 uppercase tracking-widest mb-1">Online Scan</p>
                <p class="text-lg font-black text-blue-700">{{ $totalOnlineScan }}</p>
                <p class="text-[9px] text-blue-500 font-bold">tiket</p>
            </div>
            <div class="bg-violet-50 border border-violet-100 rounded-xl p-3 text-center">
                <p class="text-[8px] font-bold text-violet-600 uppercase tracking-widest mb-1">Pendapatan</p>
                <p class="text-sm font-black text-violet-700">{{ number_format($totalOffline / 1000, 0) }}K</p>
                <p class="text-[9px] text-violet-500 font-bold">offline</p>
            </div>
        </div>

        <!-- Shift Absensi -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-3">
                <i class="bi bi-clipboard-check text-[#00a6eb] mr-1"></i>
                Absensi — {{ \Carbon\Carbon::parse($filterDate)->translatedFormat('d F Y') }}
            </h3>

            @if($absensiHariIni->count() > 0)
            <div class="space-y-2">
                @foreach($absensiHariIni as $absen)
                <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-800">{{ $absen->objekWisata->nama_objek ?? '-' }}</p>
                        @if($absen->waktu_keluar)
                        <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Selesai</span>
                        @else
                        <span class="text-[8px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">Aktif</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 text-[10px] text-slate-500">
                        <span><i class="bi bi-box-arrow-in-right text-emerald-500"></i> {{ $absen->waktu_masuk->format('H:i') }}</span>
                        <span><i class="bi bi-box-arrow-right text-rose-500"></i> {{ $absen->waktu_keluar ? $absen->waktu_keluar->format('H:i') : '-' }}</span>
                        @if($absen->durasi_menit)
                        <span><i class="bi bi-clock"></i> {{ floor($absen->durasi_menit / 60) }}j {{ $absen->durasi_menit % 60 }}m</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
                <p class="text-xs text-slate-400">Tidak ada data absensi</p>
            </div>
            @endif
        </div>

        <!-- Offline Tickets Sold -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-3">
                <i class="bi bi-cash-coin text-emerald-500 mr-1"></i>
                Tiket Offline Terjual ({{ $tiketOffline->count() }} transaksi)
            </h3>

            @if($tiketOffline->count() > 0)
            <div class="space-y-2">
                @foreach($tiketOffline as $trx)
                <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-800">{{ $trx->kode_tiket }}</p>
                            <p class="text-[9px] text-slate-400">{{ $trx->created_at->format('H:i') }} • {{ strtoupper($trx->metode_pembayaran) }}</p>
                            <div class="flex items-center gap-1 mt-1">
                                @foreach($trx->details as $d)
                                <span class="text-[8px] font-bold text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">
                                    {{ $d->kategoriTiket->nama_kategori ?? '' }} ×{{ $d->jumlah }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-sm font-black text-[#00a6eb] shrink-0 ml-3">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="mt-3 bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-xl p-4 text-center">
                <p class="text-[9px] text-white/70 uppercase tracking-widest font-bold mb-1">Total Penjualan Offline</p>
                <p class="text-2xl font-black text-white">Rp {{ number_format($totalOffline, 0, ',', '.') }}</p>
            </div>
            @else
            <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
                <p class="text-xs text-slate-400">Tidak ada penjualan offline</p>
            </div>
            @endif
        </div>

        <!-- Online Tickets Scanned -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-3">
                <i class="bi bi-qr-code-scan text-blue-500 mr-1"></i>
                Tiket Online Di-scan ({{ $tiketOnlineScan->count() }} tiket)
            </h3>

            @if($tiketOnlineScan->count() > 0)
            <div class="space-y-2">
                @foreach($tiketOnlineScan as $trx)
                <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-800">{{ $trx->kode_tiket }}</p>
                            <p class="text-[9px] text-slate-400">Scan: {{ $trx->waktu_scan ? $trx->waktu_scan->format('H:i') : '-' }}</p>
                        </div>
                        <p class="text-sm font-bold text-slate-600 shrink-0 ml-3">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
                <p class="text-xs text-slate-400">Tidak ada tiket online yang di-scan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
