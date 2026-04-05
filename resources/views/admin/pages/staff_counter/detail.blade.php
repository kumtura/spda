@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <a href="{{ url('administrator/staff_counter') }}" class="h-10 w-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="h-14 w-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-xl uppercase">
                {{ substr($staff->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $staff->name }}</h1>
                    @if($activeShift)
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> On Duty sejak {{ $activeShift->waktu_masuk->format('H:i') }}
                        </span>
                    @else
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[10px] font-bold">Off Duty</span>
                    @endif
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-400 font-medium mt-0.5">
                    <span>{{ $staff->email }}</span>
                    <span>{{ $staff->no_wa }}</span>
                </div>
            </div>
        </div>
        <!-- Month Filter -->
        <form method="GET" class="flex items-center gap-2">
            <select name="bulan" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 outline-none">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endfor
            </select>
            <select name="tahun" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 outline-none">
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition-all">Tampilkan</button>
        </form>
    </div>

    <!-- Assigned Objek Wisata -->
    @if($assignments->count() > 0)
    <div class="flex flex-wrap gap-2">
        @foreach($assignments as $a)
            @if($a->objekWisata)
            <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-xl text-xs font-bold border border-blue-100">
                <svg class="w-3.5 h-3.5 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                {{ $a->objekWisata->nama_objek }}
            </span>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
            <p class="text-2xl font-black text-slate-800">{{ $totalHariMasuk }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hari Masuk</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
            <p class="text-2xl font-black text-slate-800">{{ floor($totalJamKerja / 60) }}j {{ $totalJamKerja % 60 }}m</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Jam Kerja</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
            <p class="text-2xl font-black text-slate-800">{{ $rataJamPerHari }}j</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata/Hari</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
            <p class="text-2xl font-black text-emerald-600">{{ $totalTiket }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Tiket</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
            <p class="text-2xl font-black text-blue-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Penjualan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Penjualan Per Objek Wisata -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-700 mb-4">Penjualan Per Objek Wisata</h3>
            @if($penjualanPerObjek->count() > 0)
            <div class="space-y-3">
                @foreach($penjualanPerObjek as $po)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-700">{{ $po->nama_objek }}</p>
                        <p class="text-[10px] text-slate-400">{{ $po->jumlah_tiket }} tiket</p>
                    </div>
                    <p class="text-sm font-black text-emerald-600">Rp {{ number_format($po->total_pendapatan, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-slate-400 text-center py-6">Belum ada data penjualan.</p>
            @endif
        </div>

        <!-- Penjualan Per Hari -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-700 mb-4">Penjualan Per Hari</h3>
            @if($penjualanPerHari->count() > 0)
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($penjualanPerHari as $ph)
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <div>
                        <p class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($ph->tanggal)->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400">{{ $ph->jumlah_tiket }} tiket</p>
                    </div>
                    <p class="text-sm font-black text-slate-700">Rp {{ number_format($ph->total_pendapatan, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-slate-400 text-center py-6">Belum ada data penjualan.</p>
            @endif
        </div>
    </div>

    <!-- Riwayat Absensi -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-700">Riwayat Absensi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Tanggal</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Objek Wisata</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Masuk</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Keluar</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Durasi</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatAbsensi as $absen)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-700 whitespace-nowrap">{{ $absen->waktu_masuk->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold">{{ $absen->objekWisata?->nama_objek ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3 font-medium text-emerald-600 whitespace-nowrap">{{ $absen->waktu_masuk->format('H:i') }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if($absen->waktu_keluar)
                                <span class="font-medium text-slate-600">{{ $absen->waktu_keluar->format('H:i') }}</span>
                            @else
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">Masih Shift</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-500">
                            @if($absen->waktu_keluar)
                                {{ floor($absen->waktu_masuk->diffInMinutes($absen->waktu_keluar) / 60) }}j {{ $absen->waktu_masuk->diffInMinutes($absen->waktu_keluar) % 60 }}m
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-400 max-w-xs truncate">{{ $absen->catatan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm font-semibold text-slate-400">
                            Belum ada riwayat absensi bulan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-700">Transaksi Terbaru (20 Terakhir)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Waktu</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Kode Tiket</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Objek Wisata</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Pengunjung</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Metode</th>
                        <th class="text-right px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerbaru as $trx)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 text-slate-600 font-medium whitespace-nowrap">{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-3 font-mono text-slate-700 font-bold">{{ $trx->kode_tiket }}</td>
                        <td class="px-5 py-3">
                            <span class="text-slate-700 font-medium">{{ $trx->objekWisata?->nama_objek ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $trx->nama_pengunjung }}</td>
                        <td class="px-5 py-3">
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded font-bold text-[10px]">{{ $trx->metode_pembelian }}</span>
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-emerald-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm font-semibold text-slate-400">
                            Belum ada transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
