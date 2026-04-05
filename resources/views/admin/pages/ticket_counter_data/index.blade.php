@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Ticket Counter</h1>
            <p class="text-slate-500 font-medium text-sm">Monitoring staff on-duty, penjualan tiket hari ini & statistik.</p>
        </div>
        <a href="{{ url('administrator/ticket_counter_data/history') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl shadow-lg shadow-slate-200/50 transition-all text-xs font-black uppercase tracking-widest active:scale-95">
            <i class="bi bi-clock-history"></i> Riwayat Pembelian
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-primary-light flex items-center justify-center">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Staff</p>
                    <p class="text-2xl font-black text-slate-800">{{ $totalStaff }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="bi bi-person-check-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">On Duty</p>
                    <p class="text-2xl font-black text-emerald-600">{{ $staffAktifHariIni }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-primary-light flex items-center justify-center">
                    <i class="bi bi-ticket-perforated-fill text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiket Hari Ini</p>
                    <p class="text-2xl font-black text-slate-800">{{ $totalTiketHariIni }}</p>
                    <div class="flex gap-2 mt-1">
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $offlineHariIni }} offline</span>
                        <span class="text-[9px] font-bold text-primary-light bg-blue-50 px-1.5 py-0.5 rounded">{{ $onlineHariIni }} online</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                    <i class="bi bi-cash-stack text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendapatan Hari Ini</p>
                    <p class="text-xl font-black text-slate-800">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff On Duty -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
                Staff Sedang Bertugas
            </h2>
        </div>
        @if($staffOnDuty->count() > 0)
        <div class="divide-y divide-slate-100">
            @foreach($staffOnDuty as $shift)
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 text-primary-light flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($shift->user->name ?? '-', 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $shift->user->name ?? '-' }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $shift->objekWisata->nama_objek ?? '-' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                        <i class="bi bi-clock mr-1"></i>Masuk {{ $shift->waktu_masuk->format('H:i') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center">
            <i class="bi bi-person-slash text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400 font-medium">Tidak ada staff yang sedang bertugas saat ini</p>
        </div>
        @endif
    </div>

    <!-- Per Objek Stats -->
    @if($perObjekHariIni->count() > 0)
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800">Penjualan Per Objek Wisata Hari Ini</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead><tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <th class="text-left px-5 py-3">Objek Wisata</th>
                    <th class="text-center px-3 py-3">Total Tiket</th>
                    <th class="text-center px-3 py-3">Offline</th>
                    <th class="text-center px-3 py-3">Online</th>
                    <th class="text-right px-5 py-3">Pendapatan</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($perObjekHariIni as $objek)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-bold text-slate-800">{{ $objek->nama_objek }}</td>
                        <td class="px-3 py-3 text-center font-bold">{{ $objek->jumlah_tiket }}</td>
                        <td class="px-3 py-3 text-center"><span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded">{{ $objek->offline_count }}</span></td>
                        <td class="px-3 py-3 text-center"><span class="bg-blue-50 text-primary-light font-bold px-2 py-0.5 rounded">{{ $objek->online_count }}</span></td>
                        <td class="px-5 py-3 text-right font-black text-slate-800">Rp {{ number_format($objek->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">Transaksi Terbaru</h2>
            <a href="{{ url('administrator/ticket_counter_data/history') }}" class="text-[10px] font-black text-primary-light hover:text-primary-dark uppercase tracking-widest">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead><tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <th class="text-left px-5 py-3">Kode Tiket</th>
                    <th class="text-left px-3 py-3">Objek Wisata</th>
                    <th class="text-center px-3 py-3">Metode</th>
                    <th class="text-center px-3 py-3">Pembayaran</th>
                    <th class="text-center px-3 py-3">Petugas</th>
                    <th class="text-right px-5 py-3">Total</th>
                    <th class="text-right px-5 py-3">Waktu</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentTransactions as $trx)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-mono font-bold text-slate-800">{{ $trx->kode_tiket }}</td>
                        <td class="px-3 py-3 font-medium text-slate-700">{{ $trx->objekWisata->nama_objek ?? '-' }}</td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded font-bold {{ $trx->metode_pembelian == 'offline' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-primary-light' }}">
                                <i class="bi {{ $trx->metode_pembelian == 'offline' ? 'bi-shop' : 'bi-globe' }}"></i>
                                {{ ucfirst($trx->metode_pembelian) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center font-medium text-slate-600">{{ strtoupper($trx->metode_pembayaran) }}</td>
                        <td class="px-3 py-3 text-center text-slate-500">{{ $trx->petugas->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-black text-slate-800">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-slate-400">{{ $trx->created_at->format('d/m H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
