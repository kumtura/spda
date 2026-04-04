@extends('mobile_layout')

@section('isi_menu')
@php
    $kelianBanjar = Auth::user()->banjar;
    $currentMonth = (int)date('m');
    $currentYear = (int)date('Y');
    $selectedMonth = (int)request()->get('bulan', $currentMonth);
    $selectedYear = (int)request()->get('tahun', $currentYear);

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    if(!$kelianBanjar) {
        $usahaList = collect([]);
    } else {
        $usahaList = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->leftJoin('tb_kategori_usaha','tb_kategori_usaha.id_kategori_usaha','tb_usaha.id_jenis_usaha')
            ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
            ->where('tb_usaha.aktif_status', '1')
            ->select('tb_usaha.*','tb_detail_usaha.*','tb_kategori_usaha.nama_kategori_usaha')
            ->orderBy('tb_detail_usaha.nama_usaha','asc')
            ->get();
    }

    $totalUsaha = $usahaList->count();
    $sudahBayar = 0;
    $belumBayar = 0;
    $totalPunia = 0;

    $usahaWithPayment = [];
    foreach($usahaList as $usaha) {
        $payment = App\Models\Danapunia::where('id_usaha', $usaha->id_usaha)
            ->where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where('bulan_punia', $selectedMonth)
            ->where('tahun_punia', $selectedYear)
            ->first();

        if($payment) {
            $sudahBayar++;
            $totalPunia += $payment->jumlah_dana;
        } else {
            $belumBayar++;
        }

        $usahaWithPayment[] = [
            'usaha' => $usaha,
            'payment' => $payment,
        ];
    }
@endphp

<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ 
    selectedMonth: {{ $selectedMonth }},
    selectedYear: {{ $selectedYear }},
    searchQuery: '',
    showFilter: false
}">
    <!-- Back + Header -->
    <div>
        <a href="{{ url('administrator/kelian/punia') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-3">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali ke Punia</span>
        </a>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Usaha</h1>
                <p class="text-slate-400 text-[10px] mt-1">Banjar {{ $kelianBanjar ? $kelianBanjar->nama_banjar : '-' }}</p>
            </div>
            <button @click="showFilter = !showFilter" class="h-8 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center gap-1.5 transition-colors">
                <i class="bi bi-funnel text-sm"></i>
                <span class="text-[10px] font-bold">Filter</span>
            </button>
        </div>
    </div>

    <!-- Filter Panel -->
    <div x-show="showFilter" x-cloak x-transition class="bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Bulan</label>
                <select x-model="selectedMonth" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 bg-white">
                    @foreach($months as $num => $name)
                    <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Tahun</label>
                <select x-model="selectedYear" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 bg-white">
                    @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <button @click="window.location.href = '{{ url('administrator/kelian/data_usaha') }}?bulan=' + selectedMonth + '&tahun=' + selectedYear" 
                class="w-full bg-[#00a6eb] text-white text-xs font-bold py-2.5 rounded-lg hover:bg-[#0090d0] transition-colors">
            Terapkan Filter
        </button>
    </div>

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-building text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ $months[$selectedMonth] }} {{ $selectedYear }}</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia Unit Usaha</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalPunia, 0, ',', '.') }}</h3>
            
            <div class="grid grid-cols-3 gap-3 pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Total Usaha</p>
                    <p class="font-bold text-sm">{{ $totalUsaha }}</p>
                </div>
                <div class="text-center">
                    <p class="text-white/60 text-[9px] mb-0.5">Sudah Bayar</p>
                    <p class="font-bold text-sm text-emerald-300">{{ $sudahBayar }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Belum Bayar</p>
                    <p class="font-bold text-sm text-amber-300">{{ $belumBayar }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="relative">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
        <input type="text" x-model="searchQuery" placeholder="Cari usaha..." 
               class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#00a6eb]/30 focus:ring-1 focus:ring-[#00a6eb]/20">
    </div>

    <!-- Usaha List -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Daftar Unit Usaha</h3>
            <span class="text-[10px] font-medium text-slate-400">{{ $sudahBayar }}/{{ $totalUsaha }} bayar</span>
        </div>

        @if(count($usahaWithPayment) > 0)
        <div class="space-y-2.5">
            @foreach($usahaWithPayment as $item)
            @php $usaha = $item['usaha']; $payment = $item['payment']; @endphp
            <a href="{{ url('administrator/detail_usaha/'.$usaha->id_usaha) }}" 
               x-show="searchQuery === '' || '{{ strtolower($usaha->nama_usaha) }}'.includes(searchQuery.toLowerCase())"
               class="block bg-white border border-slate-100 rounded-xl p-3.5 hover:border-[#00a6eb]/30 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                        @if($usaha->logo)
                            @php
                                $logoPath = file_exists(public_path('usaha/icon/'.$usaha->logo)) 
                                    ? 'usaha/icon/'.$usaha->logo 
                                    : 'storage/usaha/icon/'.$usaha->logo;
                            @endphp
                            <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
                        @else
                            <i class="bi bi-building text-slate-300 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-0.5 truncate">{{ $usaha->nama_usaha }}</p>
                        <div class="flex items-center gap-2 text-[9px] text-slate-400">
                            @if($usaha->nama_kategori_usaha)
                            <span>{{ $usaha->nama_kategori_usaha }}</span>
                            <span>&middot;</span>
                            @endif
                            @if($payment)
                            <span class="text-emerald-500">Rp {{ number_format($payment->jumlah_dana, 0, ',', '.') }}</span>
                            @else
                            <span>Min. Rp {{ number_format($usaha->minimal_bayar ?? 0, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    @if($payment)
                    <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 shrink-0">Lunas</span>
                    @else
                    <span class="text-[8px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100 shrink-0">Belum</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-building text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada unit usaha di banjar ini</p>
        </div>
        @endif
    </div>

    <!-- Recent Payments History -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Riwayat Pembayaran Terbaru</h3>
        </div>

        @php
            if($kelianBanjar) {
                $usahaIds = $usahaList->pluck('id_usaha');
                $recentPayments = App\Models\Danapunia::whereIn('id_usaha', $usahaIds)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->orderBy('tanggal_pembayaran', 'desc')
                    ->limit(15)
                    ->get();
            } else {
                $recentPayments = collect([]);
            }
        @endphp

        @if($recentPayments->count() > 0)
        <div class="space-y-1.5">
            @foreach($recentPayments as $dp)
            @php
                $namaUsaha = $usahaList->firstWhere('id_usaha', $dp->id_usaha);
            @endphp
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $namaUsaha ? $namaUsaha->nama_usaha : 'Usaha #'.$dp->id_usaha }}</p>
                        <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400">
                            <span>{{ $dp->tanggal_pembayaran ? \Carbon\Carbon::parse($dp->tanggal_pembayaran)->format('d M Y') : '-' }}</span>
                            @if($dp->metode_pembayaran)
                            <span>&middot; {{ strtoupper($dp->metode_pembayaran) }}</span>
                            @elseif($dp->metode)
                            <span>&middot; {{ strtoupper($dp->metode) }}</span>
                            @endif
                            <span>&middot; Bln {{ $dp->bulan_punia ?? $dp->bulan ?? '-' }}</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 shrink-0">
                        +Rp {{ number_format($dp->jumlah_dana, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
            <p class="text-xs text-slate-400">Belum ada riwayat pembayaran</p>
        </div>
        @endif
    </div>
</div>
@endsection
