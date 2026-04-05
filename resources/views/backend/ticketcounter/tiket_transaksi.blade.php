@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10 flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/ticketcounter/tiket') }}" class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                <i class="bi bi-arrow-left text-white text-lg"></i>
            </a>
            <div>
                <h1 class="text-lg font-black">Transaksi Masuk</h1>
                <p class="text-[10px] text-white/80">Pembayaran online dari pengunjung</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="relative z-10 flex items-center gap-3 mt-2">
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                <p class="text-[8px] font-bold text-white/70 uppercase tracking-widest mb-0.5">Total Completed</p>
                <p class="text-lg font-black">Rp {{ number_format($totalCompleted, 0, ',', '.') }}</p>
            </div>
            <div class="flex-1 bg-white/20 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                <p class="text-[8px] font-bold text-white/70 uppercase tracking-widest mb-0.5">Jumlah Transaksi</p>
                <p class="text-lg font-black">{{ $totalTransaksi }}</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">
        <!-- Filter -->
        <form method="GET" action="{{ url('administrator/ticketcounter/tiket/transaksi') }}" class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm space-y-3">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $filterDate }}" 
                        class="w-full px-2.5 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                    <select name="status" class="w-full px-2.5 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        <option value="">Semua</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode tiket / nama..."
                    class="flex-1 px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                <button type="submit" class="px-4 py-2 bg-[#00a6eb] text-white text-xs font-bold rounded-lg active:scale-95 transition-all">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <!-- Transaction List -->
        @if($transaksi->count() > 0)
        <div class="space-y-3">
            @foreach($transaksi as $trx)
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm" x-data="{ open: false }">
                <div class="flex items-start justify-between cursor-pointer" @click="open = !open">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-black text-slate-800">{{ $trx->kode_tiket }}</span>
                            @if($trx->status_pembayaran == 'completed')
                            <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">PAID</span>
                            @elseif($trx->status_pembayaran == 'pending')
                            <span class="text-[8px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">PENDING</span>
                            @else
                            <span class="text-[8px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">FAILED</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 truncate">{{ $trx->objekWisata->nama_objek ?? '-' }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[9px] text-slate-400">
                                <i class="bi bi-clock"></i> {{ $trx->created_at->format('H:i') }}
                            </span>
                            <span class="text-[9px] text-slate-400">
                                <i class="bi bi-credit-card"></i> {{ strtoupper($trx->metode_pembayaran ?? '-') }}
                            </span>
                            <span class="text-[9px] text-slate-400">
                                <i class="bi bi-cart"></i> {{ $trx->metode_pembelian }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-3">
                        <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            @if($trx->status_tiket == 'sudah_digunakan')
                            <span class="text-[8px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">USED</span>
                            @elseif($trx->status_tiket == 'belum_digunakan')
                            <span class="text-[8px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">ACTIVE</span>
                            @else
                            <span class="text-[8px] font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">EXPIRED</span>
                            @endif
                            <i class="bi bi-chevron-down text-slate-300 text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </div>
                </div>

                <!-- Expandable Detail -->
                <div x-show="open" x-collapse class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                    @if($trx->nama_pengunjung)
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400">Nama</span>
                        <span class="font-bold text-slate-700">{{ $trx->nama_pengunjung }}</span>
                    </div>
                    @endif
                    @if($trx->email)
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400">Email</span>
                        <span class="font-bold text-slate-700">{{ $trx->email }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400">Tanggal Kunjungan</span>
                        <span class="font-bold text-slate-700">{{ $trx->tanggal_kunjungan ? $trx->tanggal_kunjungan->format('d M Y') : '-' }}</span>
                    </div>
                    @if($trx->details->count() > 0)
                    <div class="bg-slate-50 rounded-lg p-2.5 space-y-1">
                        @foreach($trx->details as $detail)
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-500">{{ $detail->kategoriTiket->nama_kategori ?? '-' }} × {{ $detail->jumlah }}</span>
                            <span class="font-bold text-slate-700">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @if($trx->waktu_scan)
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400">Waktu Scan</span>
                        <span class="font-bold text-slate-700">{{ $trx->waktu_scan->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-8 text-center">
            <i class="bi bi-receipt text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Tidak ada transaksi ditemukan</p>
        </div>
        @endif
    </div>
</div>
@endsection
